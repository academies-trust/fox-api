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
use App\API\transformers\GroupTransformer;
use Illuminate\Http\Request;
use App\Group;

class GroupController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		Auth::user()->load(['groups' => function ($q) use ( &$groups) {
		       $groups = $q->limit($this->per_page)
		       			->skip($this->current)
		       			->get()
		       			->unique();
		}]);
		if($groups) {
			$cursor = new Cursor($this->current, $this->prev, $this->next, $groups->count());
			return $this->respondWithCursor($groups, new GroupTransformer, $cursor);
		}
		return $this->errorNotFound('No groups found');
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
				'open' => 'required|boolean',
				'service_provider' => 'required|boolean',
			]
		);

		$group = new Group();
		$group->name = $request->name;
		$group->open = (bool) $request->open;
		$group->service_provider = (bool) $request->service_provider;

		if($group->save()) {
			$group->users()->attach(Auth::user()->id, ['permission_id' => 1]);
			return $this->respondWithItem($group, new GroupTransformer);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Group $group)
	{
		Auth::user()->load(['groups' => function ($q) use ( &$groups ) {
		    $groups = $q->get()->unique();
		}]);
		if($groups->contains($group)) {
			return $this->respondWithItem($group, new GroupTransformer);	
		} else {
			return $this->errorNotFound('Group not found');	
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Group $group, Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			[
				'name' => 'sometimes|required',
				'open' => 'sometimes|required|boolean',
				'service_provider' => 'sometimes|required|boolean',
			]
		);

		$group->name = ($request->name) ? $request->name : $group->name;
		$group->open = ($request->open) ? (bool) $request->open : $group->open;
		$group->service_provider = ($request->service_provider) ? (bool) $request->service_provider : $group->service_provider;

		if($group->save())
		{
			return $this->respondWithItem($group, new GroupTransformer);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Group $group)
	{
		if($group->delete()) {
			return $this->respondSuccess('Group Deleted');
		}
	}

}
