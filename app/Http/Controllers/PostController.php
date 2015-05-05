<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Post;

use Illuminate\Http\Request;

class PostController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		
		Auth::user()->load(['groups.posts' => function ($q) use ( &$posts, $this->per_page, $this->current ) {
		       $posts = $q->limit($this->per_page)
		       			->skip($this->current)
		       			->get()
		       			->unique();
		}]);
		if($posts) {
			
			$cursor = new Cursor($this->current, $this->prev, $this->next, $posts->count());
			return $this->respondWithCursor($posts, new PostTransformer, $cursor);
		}
		return $this->errorNotFound('No posts found');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Post $post)
	{
		//is this controller really necessary?
	}

	public function includeType(Post $post)
	{
        $type = $post->postable;
        $transformer = getTransformer($type, 'App\API\Transformers\\');
        return $this->respondWithItem($type, new $transformer);
	}

	function getTransformer($type, $namespace)
	{
	    return $namespace .
	        join('', array_slice(explode('\\', $type), -1)) .
	        'Transformer';
	}


}
