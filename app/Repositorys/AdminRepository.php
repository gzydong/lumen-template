<?php

namespace App\Repositorys;

use App\Models\Admin;

/**
 * Class AdminRepository
 *
 * @package App\Repositorys
 */
class AdminRepository
{
    /**
     * 获取管理员列表
     *
     * @param array $params
     * @return mixed
     */
    public function admins(array $params)
    {
        return Admin::get()->toArray();
    }

    /**
     * 通过用户名查询管理员信息
     *
     * @param string $username
     * @return mixed
     */
    public function findByUserName(string $username)
    {
        return Admin::where('username', $username)->first();
    }
}
