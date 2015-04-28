<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Response;

			//forbidden
	        //internal error
	        //unauthorized
	        //wrongargs
	        //validation
	        //conflict

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];
	protected $statusCode = 200;

	const CODE_WRONG_ARGS = 'API-ARGS';
    const CODE_NOT_FOUND = 'API-LIKETHEWIND';
    const CODE_INTERNAL_ERROR = 'API-AAAGGH';
    const CODE_UNAUTHORISED = 'API-MAYBGTFO';
    const CODE_FORBIDDEN = 'API-GTFO';
    const CODE_INVALID_MIME_TYPE = 'API-UMWUT';
    const CODE_CONFLICT = 'API-MULTIVERSE';
    const CODE_VALIDATION = 'API-MALFORMED';

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		if ($this->isHttpException($e))
        {
        	if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException)
	        {
	        	$message = ($e->getMessage() == '') ? 'One or more resource was not found' : $e->getMessage();
	            return $this->errorNotFound($message);
	        }
	        if ($e instanceof UnauthorizedHttpException)
	        {
	        	$message = ($e->getMessage() == '') ? 'You don\'t have access to this resource' : $e->getMessage();
	        	return $this->errorUnauthorised($message);
	        }
			if ($e instanceof AccessDeniedHttpException)
			{
				$message = ($e->getMessage() == '') ? 'Forbidden' : $e->getMessage();
				return $this->errorForbidden($message);
			}
			if ($e instanceof FatalErrorException)
			{
				$message = ($e->getMessage() == '') ? 'Internal Error' : $e->getMessage();
				return $this->errorInternalError($message);
			}
			if ($e instanceof ConflictHttpException)
			{
				$message = ($e->getMessage() == '') ? 'Request unprocessable due to a conflict' : $e->getMessage();
				return $this->errorConflict($message);
			}
			if ($e instanceof BadRequestHttpException)
			{
				$message = ($e->getMessage() == '') ? 'Your request was unprocessable - wrong arguments' : $e->getMessage();
				return $this->errorWrongArgs($message);
			}
			if ($e instanceof NotAcceptableHttpException)
			{
				$message = ($e->getMessage() == '') ? 'Your request held invalid or incomplete data' : $e->getMessage();
				return $this->errorValidation($message);
			}
        }
        if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
	        $message = ($e->getMessage() == '') ? 'Token Expired' : $e->getMessage();
        	return $this->errorUnauthorised($message);
	    }
	    if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
	        $message = ($e->getMessage() == '') ? 'Token Invalid' : $e->getMessage();
        	return $this->errorUnauthorised($message);
	    }
        else
        {
            return parent::render($request, $e);
        }
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
	* @param array $array
	* @param array $headers
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function respondWithArray(array $array, array $headers = [])
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
	public function respondWithError($message, $errorCode)
    {
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
	public function errorForbidden($message)
	{
		return $this->setStatusCode(403)->respondWithError($message, self::CODE_FORBIDDEN);
	}
	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorInternalError($message)
	{
		return $this->setStatusCode(500)->respondWithError($message, Self::CODE_INTERNAL_ERROR);
	}
	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorNotFound($message)
	{
		return $this->setStatusCode(404)->respondWithError($message, Self::CODE_NOT_FOUND);
	}
	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorUnauthorised($message)
	{
		return $this->setStatusCode(401)->respondWithError($message, Self::CODE_UNAUTHORISED);
	}
	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorWrongArgs($message)
	{
		return $this->setStatusCode(403)->respondWithError($message, Self::CODE_WRONG_ARGS);
	}

	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorConflict($message)
	{
		return $this->setStatusCode(409)->respondWithError($message, Self::CODE_CONFLICT);
	}

	/**
	* @param string $message
	* @return \Symfony\Component\HttpFoundation\Response
	*/
	public function errorValidation($message)
	{
		return $this->setStatusCode(422)->respondWithError($message, Self::CODE_VALIDATION);
	}

}