<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->post('api/login', 'AuthController@login');

$router->group(['prefix' => 'api', 'middleware' => 'auth:api'], function () use ($router) {
    $router->post('logout', 'AuthController@logout');
    $router->get('verifyAuth', 'AuthController@me');
});

$router->group(['prefix' => 'api', 'middleware' => 'admin:api'], function () use ($router) {

    $router->get('properties', 'PropertiesController@getWithoutTrashed');
    $router->post('properties', 'PropertiesController@create');
    $router->put('properties', 'PropertiesController@update');
    $router->delete('properties', 'PropertiesController@delete');

    $router->get('reports', 'ReportsController@getWithoutTrashed');
    $router->post('reports', 'ReportsController@create');
    $router->put('reports', 'ReportsController@update');
    $router->delete('reports', 'ReportsController@delete');
});
