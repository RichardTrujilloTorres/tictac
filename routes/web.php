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


$router->group([
    'middleware' => 'auth',
    'prefix' => '/api'
], function () use ($router) {

    /**
     * Activities
     */
    $router->group([
        'prefix' => 'activities',
    ], function () use ($router) {
        $router->get('/', 'ActivitiesController@index');
        $router->post('/', 'ActivitiesController@store');
        $router->get('/{id}', 'ActivitiesController@show');
        $router->put('/{id}', 'ActivitiesController@update');
        $router->delete('/{id}', 'ActivitiesController@delete');
    });
});

