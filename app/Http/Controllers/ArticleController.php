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
use App\API\transformers\ArticleTransformer;
use App\API\transformers\ArticleContentTransformer;
use Illuminate\Http\Request;
use App\Article;
use App\Comment;
use App\Group;

class ArticleController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		Auth::user()->load(['groups.articles' => function ($q) use ( &$articles) {
		       $articles = $q->active()
		       			->limit($this->per_page)
		       			->skip($this->current)
		       			->get()
		       			->unique();
		}]);
		if($articles) {
			$cursor = new Cursor($this->current, $this->prev, $this->next, $articles->count());
			return $this->respondWithCursor($articles, new ArticleTransformer, $cursor);
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
				'content' => 'required|min:10',
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
				'user_id' => Auth::user()->id,
				'published_at' => $request->published,
			]);
			$content = $this->createArticleContent($article, $request, 'New Article');
			if($content)
			{
				$article->content_id = $content->id;
				$article->save();
				return $this->respondWithItem($article, new ArticleTransformer);
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
	public function show(Article $article)
	{
		Auth::user()->load(['groups.articles' => function ($q) use ( &$articles ) {
		    $articles = $q->get()->unique();
		}]);
		if($articles->contains($article)) {
			return $this->respondWithItem($article, new ArticleTransformer);	
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
	public function update(Article $article, Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			[
				// post
				'published' => 'sometimes|date',
				// article
				'title' => 'sometimes|min:3|max:255',
				'group' => 'sometimes|integer',
				'comments' => 'sometimes|boolean',
				'content' => 'sometimes|min:10',
				'help' => 'sometimes|boolean',
				'approve' => 'sometimes|boolean|required_with:content',
				'revision' => 'sometimes|integer',
				'reason' => 'required_with:title|max:255',
				'deleted' => 'sometimes|date',
			]
		);
		if($validator->passes())
		{
			// TBD check user has write access to group
			if($article->deleted_at !== null && $request->has('deleted') && $request->deleted === null) {
				$article->restore();
				return $this->respondWithItem($article, new ArticleTransformer);
			} else {
				$article->allow_comments = ($request->exists('comments')) ? (bool) $request->comments : $article->allow_comments;
				$article->group_id = ($request->exists('group')) ? (bool) $request->group : $article->group_id;
				if($request->title && $request->content)
				{
					$content = $this->createArticleContent($article, $request, $request->reason);
					if($content)
					{
						return $this->respondWithItem($content, new ArticleContentTransformer);
					}
				} else if($request->has('revision'))
				{
					// check is admin
					$article->content_id = ($request->exists('revision')) ? $request->revision : $article->content_id;
					$this->approveContent($article, $request->revision);
				}
				if($article->save())
				{
					return $this->respondWithItem($article, new ArticleTransformer);
				} else {
					return $this->errorInternal('Unable to update article');
				}
			}
		} else {
			return $this->errorValidation($validator->messages());
		}	
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Article $article)
	{
		if($article->delete()) {
			return $this->respondWithItem($article, new ArticleTransformer);
		}
	}

	protected function createArticleContent(Article $article, $request, $reason)
	{
		$content = $article->contents()->create([
			'title' => $request->title,
			'content' => $request->content,
			'parent_id' => $article->id,
			'user_id' => Auth::user()->id,
			'reason' => $reason,
		]);
		// TBD check if user is Admin, and make approved if so
		// else run event for moderation required
		return $content;
	}

	protected function approveContent(Article $article, $content_id)
	{
		$content = $article->contents()->find($content_id);
		if($content->count())
		{
			$content->approved_by = Auth::user()->id;
			$content->approved_at = \Carbon\Carbon::now();
			$content->save();
		}
		return $this->makeContent($article, $content_id);
	}

	protected function makeContent(Article $article, $content_id)
	{
		$poss_articles = $article->contents()->lists('id');
		if(in_array($content_id, $poss_articles))
		{
			$article->content_id = $content_id;
			$article->save();
			return $this->respondWithItem($article, new ArticleTransformer);
		}
		return $this->errorInternalError('The content specified to make active doesn\'t appear to be related to the article.');
	}

	public function addComment(Article $article, Request $request) {
		$validator = Validator::make(
			$request->all(),
			[
				// comment
				'content' => 'required',
			]
		);
		if($validator->passes())
		{
			// TBD check user has write access to group
			$comment = $article->comments()->create([
				'content' => $request->content,
				'postable_type' => 'App\Article',
				'postable_id' => $article->id,
				'user_id' => Auth::user()->id
			]);
			if($comment) {
				return $this->respondWithItem($article, new ArticleTransformer);
			}
			
		} else {
			return $this->errorValidation($validator->messages);
		}
	}
}