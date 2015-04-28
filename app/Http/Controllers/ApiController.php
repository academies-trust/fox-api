<?php 
namespace App\Http\Controllers;

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\CursorInterface;
use JWTAuth;
use App\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Response;
use Illuminate\Http\Request;
use Auth;

class ApiController extends Controller {

	protected 	$statusCode = 200,
				$current = 0,
				$per_page = 20,
				$next,
				$prev;

	 /**
	* @param Manager $fractal
	*/
	public function __construct(Manager $fractal, Request $request)
	{
		$this->fractal = $fractal;
		if (isset($_GET['include'])) {
		    $this->fractal->parseIncludes($_GET['include']);
		}
		$this->current = ($request->cursor) ? $request->cursor : $this->current;
		$this->per_page = ($request->cursor) ? min((int) $request->number, 100) : $this->per_page;
		$this->next = ($this->current + $this->per_page);
		$this->prev = max(($this->current - $this->per_page), 0);
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

	public function respondSuccess($message = 'Action Successful')
	{
		$array = ['message' => $message];
		return $this->respondWithArray($array);
	}

	/**
	*
	* Generates a response with a 403 Forbidden header and Error message
	*
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorForbidden($message = '')
	{
		throw new AccessDeniedHttpException($message);
	}
	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorInternalError($message = '')
	{
		throw new FatalErrorException($message);
	}
	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorNotFound($message = '')
	{
		throw new NotFoundHttpException($message);
	}
	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorUnauthorised($message = '')
	{
		throw new UnauthorizedHttpException($message);
	}
	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorWrongArgs($message = '')
	{
		throw new BadRequestHttpException($message);
	}

	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorConflict($message = '')
	{
		throw new ConflictHttpException($message);
	}

	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorValidation($message = '')
	{
		throw new NotAcceptableHttpException($message);
	}

}