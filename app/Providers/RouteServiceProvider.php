<?php namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		parent::boot($router);

		$router->model('articles', 'App\Post');
		$router->model('behaviours', 'App\Behaviour');
		$router->model('behaviourmodels', 'App\BehaviourModel');
		$router->model('claims', 'App\Claim');
		$router->model('claimstatuses', 'App\ClaimStatus');
		$router->model('claimupdates', 'App\ClaimUpdate');
		$router->model('classes', 'App\Class');
		$router->model('comments', 'App\Post');
		$router->model('devices', 'App\Device');
		$router->model('deviceschemes', 'App\DeviceScheme');
		$router->model('domaincontrollers', 'App\DomainController');
		$router->model('events', 'App\Post');
		$router->model('feedbacks', 'App\Feedback');
		$router->model('gradings', 'App\Grading');
		$router->model('groups', 'App\Group');
		$router->model('groupusers', 'App\GroupUser');
		$router->model('helps', 'App\Post');
		$router->model('helpcontents', 'App\HelpContent');
		$router->model('insurances', 'App\Insurance');
		$router->model('lessons', 'App\Lesson');
		$router->model('markingschemes', 'App\MarkingScheme');
		$router->model('modules', 'App\Module');
		$router->model('notices', 'App\Post');
		$router->model('notifications', 'App\Post');
		$router->model('permissions', 'App\Permission');
		$router->model('posts', 'App\Post');
		$router->model('resources', 'App\Post');
		$router->model('roles', 'App\Role');
		$router->model('schemes', 'App\Scheme');
		$router->model('servicealerts', 'App\Post');
		$router->model('sites', 'App\Site');
		$router->model('siteusers', 'App\SiteUser');
		$router->model('subjects', 'App\Subject');
		$router->model('submissions', 'App\Post');
		$router->model('tasks', 'App\Post');
		$router->model('trusts', 'App\Trust');
		$router->model('users', 'App\User');
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function($router)
		{
			require app_path('Http/routes.php');
		});
	}

}
