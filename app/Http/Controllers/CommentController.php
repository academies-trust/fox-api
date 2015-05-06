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
use App\Comment;

class CommentController extends ApiController {

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
				// comment
				'title' => 'required|min:3|max:255',
				'content' => 'required',
				'group' => 'required|integer',
				'comments' => 'required|boolean',
			]
		);
		if($validator->passes())
		{
			// TBD check user has write access to group
			$comment = Comment::create([
				'title' => $request->title,
				'content' => $request->content,
				'group_id' => (int) $request->group,
				'allow_comments' => (bool) $request->comments
			]);
			$post = Post::create([
				'user_id' => Auth::user()->id,
				'published_at' => $request->published,
				'postable_type' => 'App\Comment',
				'postable_id' => $comment->id
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
		Auth::user()->load(['groups.posts.comment.post' => function ($q) use ( &$comments ) {
		    $comments = $q->get()->unique();
		}]);
		if($comments->contains($post->comment)) {
			return $this->respondWithItem($post, new PostTransformer);	
		} else {
			return $this->errorNotFound('Comment not found');	
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
				// comment
				'title' => 'sometimes|max:255|min:3',
				'content' => 'sometimes',
				'group' => 'sometimes|integer',
				'comments' => 'sometimes',
			]
		);
		if($validator->passes())
		{
			// TBD check user has write access to group
			$comment = $post->comment;

			$comment->title = ($request->title) ? $request->title : $comment->title;
			$comment->content = ($request->content) ? $request->content : $comment->content;
			$comment->allow_comments = ($request->comments) ? (bool) $request->comments : $comment->allow_comments;
			$comment->group_id = ($request->group) ? (bool) $request->group : $comment->group_id;

			$comment->save();
			if($comment->save())
			{
				return $this->respondWithItem($comment->post, new PostTransformer);
			} else {
				return $this->errorInternal('Unable to update comment');
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
			return $this->respondSuccess('Comment Deleted');
		}
	}

}
