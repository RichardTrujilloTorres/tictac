<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    // 'middleware' => 'auth',
    'prefix' => '/api'
], function () use ($router) {
    /**
     * Auth
     */
    $router->post('login', 'Auth\AuthController@login');
    $router->post('register', 'Auth\AuthController@register');
    $router->post('logout', 'Auth\AuthController@logout');
    $router->post('refresh', 'Auth\AuthController@refresh');
    $router->post('me', 'Auth\AuthController@me');
});



/**
 * Activities
 */
$router->get('/api/activities', 'ActivitiesController@index');
$router->post('/api/activities', 'ActivitiesController@store');
$router->get('/api/activities/{id}', 'ActivitiesController@show');
$router->put('/api/activities/{id}', 'ActivitiesController@update');
$router->delete('/api/activities/{id}', 'ActivitiesController@delete');
