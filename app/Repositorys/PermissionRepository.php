<?php


namespace App\Repositorys;

use App\Models\Rbac\Permission;

class PermissionRepository
{

    /**
     * 添加权限信息
     *
     * @param array $data 权限信息
     * @return bool
     */
    public function create(array $data)
    {
        $result = new Permission();
        $result->route = $data['route'];
        $result->display_name = $data['display_name'];
        $result->description = $data['description'];

        return $result->save();
    }

    /**
     * 修改权限信息
     *
     * @param int $permission_id 权限ID
     * @param array $data 权限数据
     * @return mixed
     */
    public function edit(int $permission_id, array $data)
    {
        return Permission::where('id', $permission_id)->update($data);
    }

    /**
     * 删除权限信息
     *
     * @param int $permission_id 权限ID
     * @return mixed
     */
    public function delete(int $permission_id)
    {
        return Permission::where('id', $permission_id)->delete();
    }
}
