<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use Validator;
use App\API\transformers\PermissionTransformer;
use Illuminate\Http\Request;
use App\Permission;

class PermissionController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$permissions = Permission::all();
		if($permissions) {
			return $this->respondWithCollection($permissions, new PermissionTransformer);
		}
		return $this->errorNotFound('No permissions found');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Permission $permission)
	{
		return $this->repondWithItem($permission, new PermissionTransformer);
	}

}
