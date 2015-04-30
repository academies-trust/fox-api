<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use Validator;
use App\API\transformers\RoleTransformer;
use Illuminate\Http\Request;
use App\Role;

class RoleController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$roles = Role::all();
		if($roles) {
			return $this->respondWithCollection($roles, new RoleTransformer);
		}
		return $this->errorNotFound('No roles found');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Role $role)
	{
		return $this->repondWithItem($role, new RoleTransformer);
	}

}
