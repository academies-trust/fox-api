<?php namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Response;

class checkToken extends BaseMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		if (! $token = $this->auth->setRequest($request)->getToken()) {
            return Response::json(['Blah'], 401);
            throw new UnauthorizedHttpException('Bearer', 'Missing or Incomplete Token');
        }
        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            throw new UnauthorizedHttpException('Bearer', 'Token Expired');
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('Bearer', 'Token Invalid');
        }

        if (! $user) {
            throw new NotFoundHttpException('User Not Found');
        }

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
	}

}