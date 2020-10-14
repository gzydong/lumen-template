<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Rbac\RoleAdmin;
use App\Repositorys\RoleRepository;
use App\Repositorys\PermissionRepository;
use Illuminate\Http\Request;

/**
 * 后台权限管理服务
 *
 * Class RbacService
 * @package App\Services
 */
class RbacService
{
    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;

    /**
     * RbacService constructor.
     * @param RoleRepository $roleRepository
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * 添加角色信息
     *
     * @param Request $request
     * @return bool
     */
    public function createRole(Request $request)
    {
        $data = $request->only(['name', 'display_name', 'description']);
        return $this->roleRepository->create($data);
    }

    /**
     * 修改角色信息
     *
     * @param Request $request
     * @return mixed
     */
    public function editRole(Request $request)
    {
        $data = $request->only(['name', 'display_name', 'description']);
        return $this->roleRepository->edit($request->input('role_id'), $data);
    }

    /**
     * 删除角色信息
     *
     * @param int $role_id 角色ID
     * @return mixed
     */
    public function deleteRole(int $role_id)
    {
        return $this->roleRepository->delete($role_id);
    }

    /**
     * 赋予角色权限
     *
     * @param int $role_id 角色ID
     * @param array $permissions 权限列表
     * @return false
     */
    public function giveRolePermission(int $role_id, array $permissions)
    {
        $role = $this->roleRepository->findById($role_id);
        return $role ? $role->syncPerm($permissions) : false;
    }

    /**
     * 添加权限信息
     *
     * @param Request $request
     * @return mixed
     */
    public function createPermission(Request $request)
    {
        $data = $request->only(['route', 'display_name', 'description']);
        return $this->permissionRepository->create($data);
    }

    /**
     * 修改权限信息
     *
     * @param Request $request
     * @return mixed
     */
    public function editPermission(Request $request)
    {
        $data = $request->only(['route', 'display_name', 'description']);
        return $this->permissionRepository->edit($request->input('id'), $data);
    }

    /**
     * 删除权限信息
     *
     * @param int $permission_id 权限ID
     * @return mixed
     */
    public function deletePermission(int $permission_id)
    {
        return $this->permissionRepository->delete($permission_id);
    }

    /**
     * 赋予管理员角色及独立权限信息
     *
     * @param int $admin_id 管理员ID
     * @param int $role_id 角色ID
     * @param array $permissions 独立权限列表
     *
     * @return bool
     */
    public function giveAdminRole(int $admin_id, int $role_id, array $permissions = [])
    {
        try {
            RoleAdmin::updateOrCreate(['admin_id' => $admin_id], ['role_id' => $role_id]);

            $admin = Admin::where('id', $admin_id)->first();
            // 同步管理员独立权限
            $admin->perms()->sync($permissions);
        } catch (\Exception $e) {
            app('log')->error($e->getMessage(), ['exception' => $e]);
            return false;
        }

        return true;
    }
}
