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
 * AdminController 控制器分组
 */
$router->group([], function () use ($router) {
    // 授权登录接口
    $router->post('account/update-password', ['middleware' => [], 'uses' => 'AccountController@updatePassword']);
    $router->post('account/update-status', ['middleware' => ['admin_permissions'], 'uses' => 'AccountController@updateStatus']);
});

/**
 * RbacController 控制器分组
 */
$router->group(['middleware' => ['admin_permissions']], function () use ($router) {
    // 角色相关接口
    $router->post('rbac/create-role', ['middleware' => [], 'uses' => 'RbacController@createRole']);
    $router->post('rbac/edit-role', ['middleware' => [], 'uses' => 'RbacController@editRole']);
    $router->post('rbac/delete-role', ['middleware' => [], 'uses' => 'RbacController@deleteRole']);

    // 权限相关接口
    $router->post('rbac/create-permission', ['middleware' => [], 'uses' => 'RbacController@createPermission']);
    $router->post('rbac/edit-permission', ['middleware' => [], 'uses' => 'RbacController@editPermission']);
    $router->post('rbac/delete-permission', ['middleware' => [], 'uses' => 'RbacController@deletePermission']);

    // 分配角色权限
    $router->post('rbac/give-role-permission', ['middleware' => [], 'uses' => 'RbacController@giveRolePermission']);
    $router->post('rbac/give-admin-permission', ['middleware' => [], 'uses' => 'RbacController@giveAdminPermission']);
});
