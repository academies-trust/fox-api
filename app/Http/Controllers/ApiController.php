<?php 
namespace App\Http\Controllers;

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\CursorInterface;
use JWTAuth;
use App\Exceptions\Handler;
use App\User;
use Response;

class ApiController extends Controller {

	protected 	$statusCode = 200,
				$user;

	

	 /**
	* @param Manager $fractal
	*/
	public function __construct(Manager $fractal)
	{
		$this->fractal = $fractal;
		if (isset($_GET['include'])) {
		    $this->fractal->parseIncludes($_GET['include']);
		}
		if($this->middleware('checkToken')) {
			$this->user = $this->getAuthenticatedUser();	
		}
	}

	public function checkToken() {
		$this->middleware('checkToken');
	}

	public function getAuthenticatedUser()
	{
	    return $user = JWTAuth::parseToken()->authenticate();
	}



	/**
	*
	* Returns a single item JSON Response.
	*
	* @param $item
	* @param $callback
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function respondWithItem($item, $callback)
	{
		$resource = new item($item, $callback);
		$rootScope = $this->fractal->createData($resource);
		return $this->respondWithArray($rootScope->toArray());
	}
	/**
	*
	* Returns a collection as a JSON Array.
	*
	* @param $collection
	* @param $callback
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function respondWithCollection($collection, $callback)
	{
		$resource = new Collection($collection, $callback);
		$rootScope = $this->fractal->createData($resource);
		return $this->respondwithArray($rootScope->toArray());
	}
	/**
	*
	* Returns a JSON Array along with a cursor for pagination.
	*
	* @param $collection
	* @param $callback
	* @param CursorInterface $cursor
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function respondWithCursor($collection, $callback, CursorInterface $cursor)
	{
		$resource = new Collection($collection, $callback);
		$resource->setCursor($cursor);
		$rootScope = $this->fractal->createData($resource);
		return $this->respondWithArray($rootScope->toArray());
	}
	/**
	* @param array $array
	* @param array $headers
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function respondWithArray(array $array, array $headers = [])
	{
		return Response::json($array, $this->statusCode, $headers);
	}

}