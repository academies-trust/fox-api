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
use Illuminate\Http\Request;
use App\Post;
use App\Article;

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
	public function store(Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			[
				// post
				'published' => 'required|date|before:end',
				// article
				'title' => 'required|min:3|max:255',
				'content' => 'required',
				'group' => 'required|integer',
				'comments' => 'required|boolean',
			]
		);
		if($validator->passes())
		{
			// TBD check user has write access to group
			$article = Article::create([
				'title' => $request->title,
				'content' => $request->content,
				'group_id' => (int) $request->group,
				'allow_comments' => (bool) $request->comments
			]);
			$post = Post::create([
				'user_id' => Auth::user()->id,
				'published_at' => $request->published,
				'postable_type' => 'App\Article',
				'postable_id' => $article->id
			]);
			
		} else {
			return $this->errorValidation($validator->messages);
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
		Auth::user()->load(['groups.posts.article.post' => function ($q) use ( &$articles ) {
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
				'published' => 'sometimes|date',
				// article
				'title' => 'sometimes|max:255|min:3',
				'content' => 'sometimes',
				'group' => 'sometimes|integer',
				'comments' => 'sometimes',
			]
		);
		if($validator->passes())
		{
			// TBD check user has write access to group
			$article = $post->article;

			$article->title = ($request->title) ? $request->title : $article->title;
			$article->content = ($request->content) ? $request->content : $article->content;
			$article->allow_comments = ($request->comments) ? (bool) $request->comments : $article->allow_comments;
			$article->group_id = ($request->group) ? (bool) $request->group : $article->group_id;

			$article->save();
			if($article->save())
			{
				return $this->respondWithItem($article->post, new PostTransformer);
			} else {
				return $this->errorInternal('Unable to update article');
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

}
