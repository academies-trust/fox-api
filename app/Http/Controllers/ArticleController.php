<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\CursorInterface;
use Validator;
use App\API\transformers\PostTransformer;
use App\API\transformers\ArticleContentTransformer;
use Illuminate\Http\Request;
use App\Post;
use App\Article;
use App\Group;

class ArticleController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		Auth::user()->load(['groups.posts.article.post' => function ($q) use ( &$posts) {
		       $posts = $q->limit($this->per_page)
		       			->skip($this->current)
		       			->get()
		       			->unique();
		}]);
		if($posts) {
			$cursor = new Cursor($this->current, $this->prev, $this->next, $posts->count());
			return $this->respondWithCursor($posts, new PostTransformer, $cursor);
		}
		return $this->errorNotFound('No articles found');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request, Group $group)
	{
		$validator = Validator::make(
			$request->all(),
			[
				// post
				'published' => 'required|date',
				// article
				'title' => 'required|min:3|max:255',
				'content' => 'required',
				'comments' => 'required|boolean',
				'help' => 'boolean'
			]
		);
		if($validator->passes())
		{
			$help = ($request->help === 1 && \App\Group::find($group_id)->service_provider === 1);
			// TBD check user has write access to group
			$article = $group->articles()->create([
				'allow_comments' => (bool) $request->comments,
				'help'	=> (bool) $help,
			]);
			$post = Post::create([
				'user_id' => Auth::user()->id,
				'published_at' => $request->published,
				'postable_type' => 'App\Article',
				'postable_id' => $article->id
			]);

			if($this->createArticleContent($article, $request))
			{
				return $this->respondWithItem($article->post, new PostTransformer);
			} else {
				return $this->errorInternalError('Could not create article');
			}
			
		} else {
			return $this->errorValidation($validator->messages());
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Post $post)
	{
		Auth::user()->load(['groups.articles.post' => function ($q) use ( &$articles ) {
		    $articles = $q->get()->unique();
		}]);
		if($articles->contains($post->article)) {
			return $this->respondWithItem($post, new PostTransformer);	
		} else {
			return $this->errorNotFound('Article not found');	
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Post $post, Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			[
				// post
				'published' => 'sometimes|date|before:end',
				// article
				'title' => 'sometimes|min:3|max:255',
				'group' => 'sometimes|integer',
				'comments' => 'sometimes|boolean',
				'help' => 'sometimes|boolean',
				'approve' => 'sometimes|boolean|required_with:content',
			]
		);
		if($validator->passes())
		{
			// TBD check user has write access to group
			$article = $post->article;

			if($request->title && $request->content && !is_int($request->content))
			{
				$content = $this->createArticleContent($article, $request);
				if($content)
				{
					return $this->respondWithItem($content, new ArticleContentTransformer);
				}
			} else if($request->content && is_int($request->content))
			{
				// check is admin
				$article->content_id = ($request->exists('content_id')) ? $request->content_id : $article->content_id;
				$article->allow_comments = ($request->exists('comments')) ? (bool) $request->comments : $article->allow_comments;
				$article->group_id = ($request->exists('group')) ? (bool) $request->group : $article->group_id;
				if($request->exists('approve'))
				{
					if($request->approve)
					{
						$this->approveContent($article, $request->content);
					} else {
						$this->rejectContent($article, $request->content);
					}
				}
				if($article->save())
				{
					return $this->respondWithItem($article->post, new PostTransformer);
				} else {
					return $this->errorInternal('Unable to update article');
				}
			}
			
		} else {
			return $this->errorValidation($validator->messages);
		}	
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Post $post)
	{
		if($post->delete()) {
			return $this->respondSuccess('Article Deleted');
		}
	}

	protected function createArticleContent(Article $article, $request)
	{
		$content = $article->content()->create([
			'title' => $request->title,
			'content' => $request->content,
			'parent_id' => $article->id,
			'user_id' => Auth::user()->id,
		]);
		// TBD check if user is Admin, and make approved if so
		// else run event for moderation required
		return $content;
	}

	protected function approveContent(Article $article, $content_id)
	{
		$content = $article->content()->find($content_id);
		if($content->count())
		{
			$content->approved_by = Auth::user();
			$content->approved_at = Carbon::now();
			$content->save();
		}
		return $this->makeContent($article, $content_id);
	}

	protected function makeContent(Article $article, $content_id)
	{
		$poss_articles = $article->content()->lists('id');
		if(in_array($content_id, $poss_articles))
		{
			$article->content_id = $content_id;
			$article->save();
			return $this->respondWithItem($article, new ArticleTransformer);
		}
		return $this->errorInternalError('The content specified to make active doesn\'t appear to be related to the article.');
	}
}