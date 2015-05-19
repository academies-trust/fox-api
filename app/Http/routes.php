<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

## Authentication

Route::post('/token', 'UserController@signin');
Route::get('/sync', 'UserController@sync');
// testing auto commit

Route::group(['middleware' => ['checkToken' /*'jwt.refresh'*/]], function() {
	## Organisational Routes

	Route::get('/token', 'UserController@getAuthenticated');

	Route::get('trusts', 'TrustController@index');
	Route::post('trusts', 'TrustController@store');
	Route::get('trusts/{trusts}', 'TrustController@show');
	Route::patch('trusts/{trusts}', 'TrustController@update');
	Route::delete('trusts/{trusts}', 'TrustController@destroy');

	Route::post('trusts/{trusts}/sites', 'SiteController@store');
	Route::get('sites', 'SiteController@index');
	Route::get('sites/{sites}', 'SiteController@show');
	Route::patch('sites/{sites}', 'SiteController@update');
	Route::delete('sites/{sites}', 'SiteController@destroy');

	Route::get('users', 'UserController@index');
	Route::patch('users/{users}', 'UserController@update');
	Route::get('users/{users}', 'UserController@show');
	Route::delete('users/{users}', 'UserController@destroy');

	Route::get('classes', 'ClassController@index');
	Route::post('classes', 'ClassController@store');
	Route::get('classes/{classes}', 'ClassController@show');
	Route::patch('classes/{classes}', 'ClassController@update');
	Route::delete('classes/{classes}', 'ClassController@destroy');

	Route::get('groups', 'GroupController@index');
	Route::post('groups', 'GroupController@store');
	Route::get('groups/{groups}', 'GroupController@show');
	Route::patch('groups/{groups}', 'GroupController@update');
	Route::delete('groups/{groups}', 'GroupController@destroy');

	Route::get('permissions', 'PermissionController@index');
	Route::post('permissions', 'PermissionController@store');
	Route::get('permissions/{permissions}', 'PermissionController@show');
	Route::patch('permissions/{permissions}', 'PermissionController@update');
	Route::delete('permissions/{permissions}', 'PermissionController@destroy');

	Route::get('roles', 'RoleController@index');
	Route::post('roles', 'RoleController@store');
	Route::get('roles/{roles}', 'RoleController@show');
	Route::patch('roles/{roles}', 'RoleController@update');
	Route::delete('roles/{roles}', 'RoleController@destroy');

	Route::post('sites/{sites}/subjects', 'SubjectController@store');
	Route::get('subjects', 'SubjectController@index');
	Route::get('subjects/{subjects}', 'SubjectController@show');
	Route::patch('subjects/{subjects}', 'SubjectController@update');
	Route::delete('subjects/{subjects}', 'SubjectController@destroy');



	## Modules

	Route::post('groups/{groups}/articles', 'ArticleController@store');
	Route::get('articles', 'ArticleController@index');
	Route::get('articles/{articles}', 'ArticleController@show');
	Route::patch('articles/{articles}', 'ArticleController@update');
	Route::delete('articles/{articles}', 'ArticleController@destroy');

	Route::post('users/{users}/behaviour', 'BehaviourController@store');
	Route::get('behaviour', 'BehaviourController@index');
	Route::get('behaviour/{behaviour}', 'BehaviourController@show');
	Route::patch('behaviour/{behaviour}', 'BehaviourController@update');
	Route::delete('behaviour/{behaviour}', 'BehaviourController@destroy');

	Route::get('behaviourmodels', 'BehaviourModelController@index');
	Route::post('behaviourmodels', 'BehaviourModelController@store');
	Route::get('behaviourmodels/{behaviourmodels}', 'BehaviourModelController@show');
	Route::patch('behaviourmodels/{behaviourmodels}', 'BehaviourModelController@update');
	Route::delete('behaviourmodels/{behaviourmodels}', 'BehaviourModelController@destroy');

	Route::get('sites/{sites}/deviceschemes', 'DeviceSchemeController@index');
	Route::post('deviceschemes', 'DeviceSchemeController@store');
	Route::get('deviceschemes/{deviceschemes}', 'DeviceSchemeController@show');
	Route::patch('deviceschemes/{deviceschemes}', 'DeviceSchemeController@update');
	Route::delete('deviceschemes/{deviceschemes}', 'DeviceSchemeController@destroy');

	Route::get('deviceschemes/{deviceschemes}/devices', 'DeviceController@index');
	Route::post('devices', 'DeviceController@store');
	Route::get('devices/{devices}', 'DeviceController@show');
	Route::patch('devices/{devices}', 'DeviceController@update');
	Route::delete('devices/{devices}', 'DeviceController@destroy');

	Route::post('devices/{devices}/claims', 'ClaimController@store');
	Route::get('claims', 'ClaimController@index');
	Route::get('claims/{claims}', 'ClaimController@show');
	Route::patch('claims/{claims}', 'ClaimController@update');
	Route::delete('claims/{claims}', 'ClaimController@destroy');

	Route::post('claims/{claims}/claimupdates', 'ClaimUpdatesController@store');
	Route::get('claimupdates/{claimupdates}', 'ClaimUpdatesController@show');
	Route::patch('claimupdates/{claimupdates}', 'ClaimUpdatesController@update');
	Route::delete('claimupdates/{claimupdates}', 'ClaimUpdatesController@destroy');

	Route::post('articles/{articles}/comments', 'ArticleController@addComment');
	// TBD add comment controllers for other resources

	Route::get('comments/{comments}', 'CommentController@show');
	Route::patch('comments/{comments}', 'CommentController@update');
	Route::delete('comments/{comments}', 'CommentController@destroy');

	Route::post('groups/{groups}/events', 'EventController@store');
	Route::get('events', 'EventController@index');
	Route::get('events/{events}', 'EventController@show');
	Route::patch('events/{events}', 'EventController@update');
	Route::delete('events/{events}', 'EventController@destroy');

	Route::post('users/{users}/feedbacks', 'FeedbackController@store');
	Route::get('feedbacks', 'FeedbackController@index');
	Route::get('feedbacks/{feedbacks}', 'FeedbackController@show');
	Route::patch('feedbacks/{feedbacks}', 'FeedbackController@update');
	Route::delete('feedbacks/{feedbacks}', 'FeedbackController@destroy');

	Route::post('users/{users}/markingschemes', 'MarkingSchemeController@store');
	Route::get('markingschemes', 'MarkingSchemeController@index');
	Route::get('markingschemes/{markingschemes}', 'MarkingSchemeController@show');
	Route::patch('markingschemes/{markingschemes}', 'MarkingSchemeController@update');
	Route::delete('markingschemes/{markingschemes}', 'MarkingSchemeController@destroy');

	Route::post('markingschemes/{markingschemes}/gradings', 'GradingController@store');
	Route::get('gradings', 'GradingController@index');
	Route::get('gradings/{gradings}', 'GradingController@show');
	Route::patch('gradings/{gradings}', 'GradingController@update');
	Route::delete('gradings/{gradings}', 'GradingController@destroy');

	Route::get('insurances', 'InsuranceController@index');
	Route::post('insurances', 'InsuranceController@store');
	Route::get('insurances/{insurances}', 'InsuranceController@show');
	Route::patch('insurances/{insurances}', 'InsuranceController@update');
	Route::delete('insurances/{insurances}', 'InsuranceController@destroy');

	Route::get('lessons', 'LessonController@index');
	Route::post('lessons', 'LessonController@store');
	Route::get('lessons/{lessons}', 'LessonController@show');
	Route::patch('lessons/{lessons}', 'LessonController@update');
	Route::delete('lessons/{lessons}', 'LessonController@destroy');

	

	Route::get('modules', 'ModuleController@index');
	Route::post('modules', 'ModuleController@store');
	Route::get('modules/{modules}', 'ModuleController@show');
	Route::patch('modules/{modules}', 'ModuleController@update');
	Route::delete('modules/{modules}', 'ModuleController@destroy');

	Route::post('groups/{groups}/notices', 'NoticeController@store');
	Route::get('notices', 'NoticeController@index');
	Route::get('notices/{notices}', 'NoticeController@show');
	Route::patch('notices/{notices}', 'NoticeController@update');
	Route::delete('notices/{notices}', 'NoticeController@destroy');

	Route::get('notifications', 'NotificationController@index');
	Route::post('notifications', 'NotificationController@store');
	Route::get('notifications/{notifications}', 'NotificationController@show');
	Route::patch('notifications/{notifications}', 'NotificationController@update');
	Route::delete('notifications/{notifications}', 'NotificationController@destroy');

	Route::post('groups/{groups}/resources', 'ResourceController@store');
	Route::get('resources', 'ResourceController@index');
	Route::get('resources/{resources}', 'ResourceController@show');
	Route::patch('resources/{resources}', 'ResourceController@update');
	Route::delete('resources/{resources}', 'ResourceController@destroy');

	Route::post('groups/{groups}/servicealerts', 'ServiceAlertController@store');
	Route::get('servicealerts', 'ServiceAlertController@index');
	Route::get('servicealerts/{servicealerts}', 'ServiceAlertController@show');
	Route::patch('servicealerts/{servicealerts}', 'ServiceAlertController@update');
	Route::delete('servicealerts/{servicealerts}', 'ServiceAlertController@destroy');

	Route::post('tasks/{tasks}/submissions', 'SubmissionController@store');
	Route::get('submissions', 'SubmissionController@index');
	Route::get('submissions/{submissions}', 'SubmissionController@show');
	Route::patch('submissions/{submissions}', 'SubmissionController@update');
	Route::delete('submissions/{submissions}', 'SubmissionController@destroy');

	Route::get('tasks', 'TaskController@index');
	Route::post('tasks', 'TaskController@store');
	Route::get('tasks/{tasks}', 'TaskController@show');
	Route::patch('tasks/{tasks}', 'TaskController@update');
	Route::delete('tasks/{tasks}', 'TaskController@destroy');
});