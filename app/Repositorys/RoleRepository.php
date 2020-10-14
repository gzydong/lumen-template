<?php

namespace App\Repositorys;

use App\Models\Rbac\Role;
use Exception;

class RoleRepository
{
    /**
     * 创建角色
     *
     * @param array $data 角色信息
     * @return bool
     */
    public function create(array $data)
    {
        try {
            $result = new Role();
            $result->name = $data['name'];
            $result->display_name = $data['display_name'];
            $result->description = $data['description'];
            return $result->save();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 修改角色信息
     *
     * @param int $role_id 角色ID
     * @param array $data 角色数据
     * @return mixed
     */
    public function edit(int $role_id, array $data)
    {
        try {
            return Role::where('id', $role_id)->update($data);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 删除角色信息
     *
     * @param int $role_id 角色信息
     * @return mixed
     */
    public function delete(int $role_id)
    {
        return Role::where('id', $role_id)->delete();
    }

    /**
     * 获取角色信息
     *
     * @param int $role_id 角色ID
     * @param array $filed 查询字段
     * @return mixed
     */
    public function findById(int $role_id, $filed = ['*'])
    {
        return Role::where('id', $role_id)->first($filed);
    }
}
