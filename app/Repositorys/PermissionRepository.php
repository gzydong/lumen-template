<?php

namespace App\Repositorys;

use App\Models\Rbac\Permission;
use Exception;

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
        try {
            $result = new Permission();
            $result->parent_id = $data['parent_id'];
            $result->type = $data['type'];
            $result->route = $data['route'];
            $result->rule_name = $data['rule_name'];
            $result->created_at = date('Y-m-d H:i:s');
            $result->updated_at = date('Y-m-d H:i:s');
            return $result->save();
        } catch (Exception $e) {
            return false;
        }
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
        try {
            return Permission::where('id', $permission_id)->update($data);
        } catch (Exception $e) {
            return false;
        }
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

    /**
     * 获取权限列表
     *
     * @param array $field 查询字段
     * @return array
     */
    public function findAllPerms($field = ['*'])
    {
        return Permission::get($field)->toArray();
    }
}
