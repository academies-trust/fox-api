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
use App\API\transformers\SiteTransformer;
use Illuminate\Http\Request;
use App\Site;

class SiteController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		Auth::user()->load(['sites' => function ($q) use ( &$sites) {
		       $sites = $q->limit($this->per_page)
		       			->skip($this->current)
		       			->get()
		       			->unique();
		}]);
		if($sites) {
			$cursor = new Cursor($this->current, $this->prev, $this->next, $sites->count());
			return $this->respondWithCursor($sites, new SiteTransformer, $cursor);
		}
		return $this->errorNotFound('No sites found');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Site $site)
	{
		Auth::user()->load(['sites' => function ($q) use ( &$sites ) {
		    $sites = $q->get()->unique();
		}]);
		if($sites->contains($site)) {
			return $this->respondWithItem($site, new SiteTransformer);	
		} else {
			return $this->errorNotFound('Site not found');	
		}
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
