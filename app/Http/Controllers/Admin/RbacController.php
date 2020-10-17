<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ResponseCode;
use Illuminate\Http\Request;

/**
 * Class RbacController 权限管理
 *
 * @package App\Http\Controllers\Admin
 */
class RbacController extends CController
{
    /**
     * 控制器初始化...
     */
    public function __construct()
    {
        // 登录验证
        $this->middleware("auth:{$this->guard}");

        // 权限验证中间件
        $this->middleware("admin_permissions");
    }

    /**
     * 添加角色信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createRole(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        $result = services()->rbacService->createRole($request);
        if (!$result) {
            return $this->fail(ResponseCode::FAIL, '角色添加失败...');
        }

        return $this->success([], '角色添加成功...');
    }

    /**
     * 修改角色信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editRole(Request $request)
    {
        $this->validate($request, [
            'role_id' => 'required|integer:min:1',
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        $result = services()->rbacService->editRole($request);
        if (!$result) {
            return $this->fail(ResponseCode::FAIL, '角色信息修改失败...');
        }

        return $this->success([], '角色信息修改成功...');
    }

    /**
     * 删除角色
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deleteRole(Request $request)
    {
        $this->validate($request, ['role_id' => 'required|integer|min:1']);

        $result = services()->rbacService->deleteRole($request->input('role_id'));
        if (!$result) {
            return $this->fail(ResponseCode::FAIL, '角色信息删除失败...');
        }

        return $this->success([], '角色信息删除成功...');
    }

    /**
     * 创建权限
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createPermission(Request $request)
    {
        $this->validate($request, [
            'route' => 'required',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        $result = services()->rbacService->createPermission($request);
        if (!$result) {
            return $this->fail(ResponseCode::FAIL, '权限添加失败...');
        }

        return $this->success([], '权限添加成功...');
    }

    /**
     * 修改权限信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editPermission(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1',
            'route' => 'required',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        $result = services()->rbacService->editPermission($request);
        if (!$result) {
            return $this->fail(ResponseCode::FAIL, '权限修改失败...');
        }

        return $this->success([], '权限修改成功...');
    }

    /**
     * 删除权限信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePermission(Request $request)
    {
        $this->validate($request, ['id' => 'required|integer|min:1',]);

        $result = services()->rbacService->deletePermission($request->input('id'));
        if (!$result) {
            return $this->fail(ResponseCode::FAIL, '权限删除失败...');
        }

        return $this->success([], '权限删除成功...');
    }

    /**
     * 分配角色权限
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function giveRolePermission(Request $request)
    {
        $this->validate($request, [
            'role_id' => 'required|integer:min:1',
            'permissions' => 'present'
        ]);

        $permissions = $request->input('permissions', '');
        $permissions = explode(',', $permissions);
        $permissions = array_unique(array_filter($permissions));

        $result = services()->rbacService->giveRolePermission($request->input('role_id'), $permissions);
        if (!$result) {
            return $this->fail(ResponseCode::FAIL, '角色权限分配失败...');
        }

        return $this->success([], '角色权限分配成功...');
    }

    /**
     * 分配管理角色及权限
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function giveAdminPermission(Request $request)
    {
        $this->validate($request, [
            'admin_id' => 'required|integer:min:1',
            'role_id' => 'required|integer:min:1',
            'permissions' => 'present',
        ]);

        $permissions = $request->input('permissions', '');
        $permissions = explode(',', $permissions);
        $permissions = array_unique(array_filter($permissions));

        $result = services()->rbacService->giveAdminRole(
            $request->input('admin_id'),
            $request->input('role_id'),
            $permissions
        );

        if (!$result) {
            return $this->fail(ResponseCode::FAIL, '管理员权限分配失败...');
        }

        return $this->success([], '管理员权限分配成功...');
    }

    /**
     * 获取角色列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function roles(Request $request)
    {
        $this->validate($request, [
            'page' => 'required|integer:min:1',
            'page_size' => 'required|in:10,20,30,50,100',
        ]);

        $result = services()->rbacService->roles($request);
        return $this->success($result);
    }

    /**
     * 获取权限列表
     */
    public function permissions(Request $request)
    {
        $this->validate($request, [
            'page' => 'required|integer:min:1',
            'page_size' => 'required|in:10,20,30,50,100',
        ]);

        $result = services()->rbacService->permissions($request);
        return $this->success($result);
    }
}
