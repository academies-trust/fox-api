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
		$validator = Validator::make(
			$request->all(),
			[
				'name' => 'required',
				'slug' => 'required|max:10',
				'domain_controller' => 'required|integer',
				'hex_color' => 'required|max:6|min:6',
				'type' => 'required|max:10',
				'trust' => 'required|integer',
			]
		);

		$site = new Site();
		$site->name = $request->name;
		$site->slug = $request->slug;
		$site->domain_controller_id = $request->domain_controller;
		$site->hex_color = $request->hex_color;
		$site->type = $request->type;
		$site->trust_id = $request->trust;

		if($site->save()) {
			return $this->respondWithItem($site, new SiteTransformer);
		}
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
	public function update(Site $site, Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			[
				'name' => 'sometimes|required',
				'slug' => 'sometimes|required|max:10',
				'domain_controller' => 'sometimes|required|integer',
				'hex_color' => 'sometimes|required|max:6|min:6',
				'type' => 'sometimes|required|max:10',
				'trust' => 'sometimes|required|integer',
			]
		);

		$site->name = ($request->name) ? $request->name : $site->name;
		$site->slug = ($request->slug) ? $request->slug : $site->slug;
		$site->domain_controller_id = ($request->domain_controller) ? $request->domain_controller : $site->domain_controller_id;
		$site->hex_color = ($request->hex_color) ? $request->hex_color : $site->hex_color;
		$site->type = ($request->type) ? $request->type : $site->_type;
		$site->trust_id = ($request->trust) ? $request->trust : $site->trust_id;

		if($site->save())
		{
			return $this->respondWithItem($site, new SiteTransformer);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Site $site)
	{
		if($site->delete()) {
			return $this->respondSuccess('Site Deleted');
		}
	}

}
