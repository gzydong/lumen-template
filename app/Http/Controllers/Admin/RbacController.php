<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rbac\AdminPermission;
use App\Models\Rbac\Role;
use App\Models\Rbac\RoleAdmin;
use App\Traits\PagingTrait;
use Illuminate\Http\Request;

/**
 * Class RbacController 权限管理
 *
 * @package App\Http\Controllers\Admin
 */
class RbacController extends CController
{
    use PagingTrait;

    public function __construct()
    {
        $this->middleware('rbac', ['except' => [
            'getRolePerms',
            'getAdminPerms'
        ]]);
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
            return $this->fail('角色添加失败...');
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
            return $this->fail('角色信息修改失败...');
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
            return $this->fail('角色信息删除失败...');
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
            'type' => 'required|in:0,1,2',
            'parent_id' => 'required|integer|min:0',
            'rule_name' => 'required',
            'route' => 'required',
            'icon' => 'present',
            'sort' => 'present|integer|min:0|max:9999',
        ]);

        $result = services()->rbacService->createPermission($request);
        if (!$result) {
            return $this->fail('权限添加失败...');
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
            'type' => 'required|in:0,1,2',
            'parent_id' => 'required|integer|min:0',
            'rule_name' => 'required',
            'route' => 'required',
        ]);

        $result = services()->rbacService->editPermission($request);
        if (!$result) {
            return $this->fail('权限修改失败...');
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
            return $this->fail('权限删除失败...');
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
            return $this->fail('角色权限分配失败...');
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
            return $this->fail('管理员权限分配失败...');
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
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissions()
    {
        $rows = services()->rbacService->getRepository()->findAllPerms(['id', 'parent_id', 'type', 'route', 'rule_name', 'sort', 'icon']);

        $result = $this->getPagingRows($rows, count($rows), 1, 10000);
        return $this->success($result);
    }

    /**
     * 获取角色权限列表
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getRolePerms()
    {
        $this->validate(request(), [
            'role_id' => 'required|integer:min:1'
        ]);

        $rbacRepository = services()->rbacService->getRepository();

        $perms = array_map(function ($value) {
            return [
                'id' => $value['id'],
                'pid' => $value['parent_id'],
                'key' => $value['id'],
                'title' => $value['rule_name'],
            ];
        }, $rbacRepository->findAllPerms(['id', 'parent_id', 'rule_name']));

        return $this->success([
            'permissions' => $perms,
            'role_perms' => $rbacRepository->findRolePermsIds(request()->input('role_id'))
        ]);
    }

    /**
     * 获取管理员相关权限
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getAdminPerms()
    {
        $this->validate(request(), [
            'admin_id' => 'required|integer:min:1'
        ]);

        $admin_id = request()->input('admin_id');
        $rbacRepository = services()->rbacService->getRepository();

        $perms = array_map(function ($value) {
            return [
                'id' => $value['id'],
                'pid' => $value['parent_id'],
                'key' => $value['id'],
                'title' => $value['rule_name'],
            ];
        }, $rbacRepository->findAllPerms(['id', 'parent_id', 'rule_name']));

        $role_id = RoleAdmin::where('admin_id', $admin_id)->value('role_id');
        return $this->success([
            'roles' => Role::get(['id', 'display_name'])->toarray(),
            'perms' => $perms,
            'admin_perms' => AdminPermission::where('admin_id', $admin_id)->pluck('permission_id')->toArray(),
            'role_id' => $role_id ?? 0
        ], 'success');
    }
}
