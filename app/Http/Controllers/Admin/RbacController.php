<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;

/**
 * Class RbacController 权限管理
 *
 * @package App\Http\Controllers\Admin
 */
class RbacController extends CController
{
    /**
     * 创建角色
     */
    public function createRole()
    {

    }

    /**
     * 创建权限
     */
    public function createPermission()
    {

    }

    public function test(Request $request)
    {
        services()->rbacService->giveRolePermission(1, [1, 2]);
    }
}
