<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Rbac\Role;
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

    public function test(Request $request){
        $role = Role::where('id',2)->first();
        dd($role->delete());
    }
}
