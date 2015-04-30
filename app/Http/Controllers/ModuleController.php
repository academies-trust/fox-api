<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use Validator;
use App\API\transformers\ModuleTransformer;
use Illuminate\Http\Request;
use App\Module;

class ModuleController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$modules = Module::all();
		if($modules) {
			return $this->respondWithCollection($modules, new ModuleTransformer);
		}
		return $this->errorNotFound('No modules found');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Module $module)
	{
		return $this->repondWithItem($module, new ModuleTransformer);
	}

}
