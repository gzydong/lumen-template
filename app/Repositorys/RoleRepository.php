<?php

namespace App\Repositorys;


use App\Models\Rbac\Role;

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
        $result = new Role();
        $result->name = $data['name'];
        $result->display_name = $data['display_name'];
        $result->description = $data['description'];
        $result->created_at = $data['created_at'];
        $result->updated_at = $data['updated_at'];
        return $result->save();
    }

    /**
     * 修改角色信息
     *
     * @param int $role_id 角色ID
     * @param array $data 角色数据
     * @return mixed
     */
    public function edit(int $role_id,array $data){
        return Role::where('id',$role_id)->update($data);
    }

    /**
     * 删除角色信息
     *
     * @param int $role_id 角色信息
     * @return mixed
     */
    public function delete(int $role_id){
        return Role::where('id',$role_id)->delete();
    }
}
