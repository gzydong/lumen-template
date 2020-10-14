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
     */
    public function admins(array $params)
    {
        return Admin::get();
    }
}
