<?php 
namespace App\Http\Controllers;

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\CursorInterface;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends Controller {

	protected 	$statusCode = 200;

	const CODE_WRONG_ARGS = 'GEN-FUBARGS';
    const CODE_NOT_FOUND = 'GEN-LIKETHEWIND';
    const CODE_INTERNAL_ERROR = 'GEN-AAAGGH';
    const CODE_UNAUTHORISED = 'GEN-MAYBGTFO';
    const CODE_FORBIDDEN = 'GEN-GTFO';
    const CODE_INVALID_MIME_TYPE = 'GEN-UMWUT';
    const CODE_CONFLICT = 'GEN-MULTIVERSE';
    const CODE_VALIDATION = 'GEN-MALFORMED';

	 /**
	* @param Manager $fractal
	*/
	public function __construct(Manager $fractal)
	{
		$this->fractal = $fractal;
		if (isset($_GET['include'])) {
		    $this->fractal->parseIncludes($_GET['include']);
		}
	}

	protected function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->toUser()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }
		return $user;
    }
	
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	public function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;
		return $this;
	}


	/**
	*
	* Returns a single item JSON Response.
	*
	* @param $item
	* @param $callback
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	protected function respondWithItem($item, $callback)
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
	protected function respondWithCollection($collection, $callback)
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
	protected function respondWithCursor($collection, $callback, CursorInterface $cursor)
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
	protected function respondWithArray(array $array, array $headers = [])
	{
		return Response()->json($array, $this->statusCode, $headers);
	}
	/**
	*
	* Returns a Error as JSON.
	*
	* @param $message
	* @param $errorCode
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	protected function respondWithError($message, $errorCode)
    {
        if ($this->statusCode === 200) {
            trigger_error(
                "You better have a really good reason for erroring on a 200...",
                E_USER_WARNING
            );
        }

        return $this->respondWithArray([
            'error' => [
                'code' => $errorCode,
                'http_code' => $this->statusCode,
                'message' => $message,
            ]
        ]);
    }
	/**
	*
	* Generates a response with a 403 Forbidden header and Error message
	*
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	protected function errorForbidden($message = 'Forbidden')
	{
		return $this->setStatusCode(403)->respondWithError($message, self::CODE_FORBIDDEN);
	}
	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	protected function errorInternalError($message = 'Internal Error')
	{
		return $this->setStatusCode(500)->respondWithError($message, Self::CODE_INTERNAL_ERROR);
	}
	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	protected function errorNotFound($message = 'Not Found')
	{
		return $this->setStatusCode(404)->respondWithError($message, Self::CODE_NOT_FOUND);
	}
	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	protected function errorUnauthorised($message = 'Unauthorised')
	{
		return $this->setStatusCode(401)->respondWithError($message, Self::CODE_UNAUTHORISED);
	}
	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	protected function errorWrongArgs($message = 'Wrong Arguments')
	{
		return $this->setStatusCode(403)->respondWithError($message, Self::CODE_WRONG_ARGS);
	}

	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	protected function errorConflict($message = 'Duplicate Entry')
	{
		return $this->setStatusCode(409)->respondWithError($message, Self::CODE_CONFLICT);
	}

	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	protected function errorValidation($message = 'Your request held invalid or incomplete data')
	{
		return $this->setStatusCode(422)->respondWithError($message, Self::CODE_VALIDATION);
	}

}