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
            $result->route = $data['route'];
            $result->display_name = $data['display_name'];
            $result->description = $data['description'];
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
     * 查询权限列表
     *
     * @param int $page 分页数
     * @param int $page_size 分页大小
     * @param array $params 查询参数
     * @return array
     */
    public function permissions(int $page, int $page_size, array $params = [])
    {
        $rowObj = Permission::select(['id', 'route', 'display_name', 'description', 'created_at', 'updated_at']);

        $total = $rowObj->count();
        $rows = $rowObj->forPage($page, $page_size)->get()->toArray();
        return $this->getPagingRows($rows, $total, $page, $page_size);
    }
}
