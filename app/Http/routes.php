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

Route::post('/signup', function () {
   $credentials = Input::only('email', 'password');
   $credentials['password'] = Hash::make($credentials['password']);
   $user = new \App\User;
   try {
       $user = $user->create($credentials);
   } catch (Exception $e) {
       return Response::json(['error' => 'User already exists.'], 409);
   }

   $token = JWTAuth::fromUser($user);

   return Response::json(compact('token'));
});

Route::get('sync', 'UserController@sync');

Route::post('/signin', 'UserController@signin');

Route::get('/', function() {
	return 'Home';
});

Route::get('addralph', 'UserController@index');
