<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Rbac\AdminPermission;
use App\Models\Rbac\Permission;
use App\Models\Rbac\RoleAdmin;
use App\Models\Rbac\RolePermission;
use App\Repositorys\AdminRepository;
use App\Repositorys\RbacRepository;
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
     * @var RbacRepository
     */
    protected $rbacRepository;

    public function __construct(RbacRepository $rbacRepository)
    {
        $this->rbacRepository = $rbacRepository;
    }

    public function getRepository()
    {
        return $this->rbacRepository;
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

        return $this->rbacRepository->insertRole($data);
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
        return $this->rbacRepository->updateRole($request->input('role_id'), $data);
    }

    /**
     * 删除角色信息
     *
     * @param int $role_id 角色ID
     * @return mixed
     */
    public function deleteRole(int $role_id)
    {
        return $this->rbacRepository->deleteRole($role_id);
    }

    /**
     * 赋予角色权限
     *
     * @param int $role_id 角色ID
     * @param array $permissions 权限列表
     * @return mixed
     */
    public function giveRolePermission(int $role_id, array $permissions)
    {
        $role = $this->rbacRepository->findByRoleId($role_id);
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
        $data = $request->only(['type', 'parent_id', 'rule_name', 'route', 'icon', 'sort']);
        return $this->rbacRepository->insertPerms($data);
    }

    /**
     * 修改权限信息
     *
     * @param Request $request
     * @return mixed
     */
    public function editPermission(Request $request)
    {
        $data = $request->only(['type', 'parent_id', 'rule_name', 'route', 'icon', 'sort']);
        return $this->rbacRepository->updatePerms($request->input('id'), $data);
    }

    /**
     * 删除权限信息
     *
     * @param int $permission_id 权限ID
     * @return mixed
     */
    public function deletePermission(int $permission_id)
    {
        return $this->rbacRepository->deletePerms($permission_id);
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

    /**
     * 获取角色列表
     *
     * @param Request $request
     * @return array
     */
    public function roles(Request $request)
    {
        $params = [];

        $orderBy = $request->only(['sortField', 'sortOrder']);
        if (isset($orderBy['sortField'], $orderBy['sortOrder'])) {
            $params['order_by'] = $orderBy['sortField'];
            $params['sort'] = get_orderby_sort($orderBy['sortOrder']);
        }

        if ($username = $request->input('rolename', '')) {
            $params['display_name'] = addslashes($username);
        }

        return $this->rbacRepository->findAllRoles(
            $request->input('page'),
            $request->input('page_size'),
            $params
        );
    }

    /**
     * 获取权限列表
     *
     * @return array
     */
    public function permissions()
    {
        return $this->rbacRepository->findAllPerms(['id', 'parent_id', 'route', 'rule_name']);
    }

    /**
     * 获取管理员授权的菜单
     *
     * @param Admin $admin
     * @return mixed
     */
    public function getAuthMenus(Admin $admin)
    {
        $menus = Permission::whereIn('type', [Permission::TYPE_DIR, Permission::TYPE_MENU])->orderBy('sort', 'asc')->get(['id', 'parent_id', 'type', 'route', 'rule_name', 'icon'])->toArray();

        // 判断是否是 admin 管理员，admin 属于超级管理员拥有所有权限
        if ($admin->username != 'admin') {
            // 查询个人独立权限
            $alonePerms = AdminPermission::where('admin_id', $admin->id)->pluck('permission_id')->toArray();

            // 查询所属角色权限
            $rolePrems = [];
            $role_id = RoleAdmin::where('admin_id', $admin->id)->value('role_id');
            if ($role_id) {
                $rolePrems = RolePermission::where('role_id', $role_id)->pluck('permission_id')->toArray();
            }

            // 权限合并去重
            $allPerms = array_unique(array_merge($alonePerms, $rolePrems));

            // 过滤未拥有的权限
            foreach ($menus as $k => $menu) {
                if (!in_array($menu['id'], $allPerms)) {
                    unset($menus[$k]);
                }
            }
        }

        return $menus;
    }
}
