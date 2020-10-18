<?php

namespace App\Repositorys;

use App\Models\Rbac\Role;
use App\Traits\PagingTrait;
use Exception;

class RoleRepository
{
    use PagingTrait;

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
            $result->created_at = date('Y-m-d H:i:s');
            $result->updated_at = date('Y-m-d H:i:s');
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

    /**
     * 查询角色列表
     *
     * @param int $page 分页数
     * @param int $page_size 分页大小
     * @param array $params 查询参数
     * @return array
     */
    public function findAllRoles(int $page, int $page_size, array $params = [])
    {
        $rowObj = Role::select(['id', 'name', 'display_name', 'description', 'created_at', 'updated_at']);

        if(isset($params['display_name']) && !empty($params['display_name'])){
            $rowObj->where('display_name','like',"%{$params['display_name']}%");
        }

        $total = $rowObj->count();

        $rows = $rowObj->orderBy('id','desc')->forPage($page, $page_size)->get()->toArray();
        return $this->getPagingRows($rows, $total, $page, $page_size);
    }
}
