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

/**
 * AuthController 控制器分组
 */
$router->group([], function () use ($router) {
    // 授权登录接口
    $router->get('auth/login', ['middleware' => [], 'uses' => 'AuthController@login']);

    // 退出登录接口
    $router->get('auth/logout', ['middleware' => [], 'uses' => 'AuthController@logout']);
});

/**
 * ExampleController 控制器分组
 */
$router->group([], function () use ($router) {
    $router->get('example/index', ['middleware' => [], 'uses' => 'ExampleController@index']);
});
