<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		//'Barryvdh\Cors\Middleware\HandleCors',
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'jwt.auth' => 'Tymon\JWTAuth\Middleware\GetUserFromToken',
    	'jwt.refresh' => 'Tymon\JWTAuth\Middleware\RefreshToken',
    	'checkToken' => '\App\Http\Middleware\checkToken',
	];

}
