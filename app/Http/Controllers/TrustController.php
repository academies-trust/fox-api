<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use Validator;
use App\API\transformers\TrustTransformer;
use Illuminate\Http\Request;
use App\Trust;

class TrustController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$trusts = Trust::all();
		if($trusts) {
			return $this->respondWithCollection($trusts, new TrustTransformer);
		}
		return $this->errorNotFound('No trusts found');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Trust $trust)
	{
		return $this->repondWithItem($trust, new TrustTransformer);
	}

}
