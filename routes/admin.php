<?php
/*
|--------------------------------------------------------------------------
| Application Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 * AuthController 控制器分组
 */
$router->group([], function () use ($router) {
    // 授权登录接口
    $router->post('auth/login', ['middleware' => [], 'uses' => 'AuthController@login']);

    // 退出登录接口
    $router->get('auth/logout', ['middleware' => [], 'uses' => 'AuthController@logout']);
});


/**
 * RbacController 控制器分组
 */
$router->group([], function () use ($router) {
    // 授权登录接口
    $router->get('rbac/test', ['middleware' => ['admin_permissions'], 'uses' => 'RbacController@test']);
});
