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
use Illuminate\Http\Request;
use App\Comment;

class CommentController extends ApiController {

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Comment $comment, Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			[
				// comment
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
			$comment = $comment->comment;

			$comment->title = ($request->exists('title')) ? $request->title : $comment->title;
			$comment->content = ($request->exists('content')) ? $request->content : $comment->content;
			$comment->allow_comments = ($request->exists('comments')) ? (bool) $request->comments : $comment->allow_comments;
			$comment->group_id = ($request->exists('group')) ? (bool) $request->group : $comment->group_id;

			$comment->save();
			if($comment->save())
			{
				return $this->respondWithItem($comment, new PostTransformer);
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
	public function destroy(Comment $comment)
	{
		$comment->deleted_at = \Carbon\Carbon::now();
		if($comment->save()) {
			return $this->respondWithItem($comment, new CommentTransformer);
		}
	}

}
