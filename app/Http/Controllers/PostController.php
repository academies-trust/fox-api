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
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
