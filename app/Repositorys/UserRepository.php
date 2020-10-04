<?php

namespace App\Repositorys;

use App\Models\User;

class UserRepository
{
    public function __construct()
    {

    }

    /**
     * 获取用户信息
     *
     * @param int $user_id
     * @param array $field
     * @return \App\Models\User
     */
    public function findById($user_id, $field = ['*'])
    {
        return User::where('id', $user_id)->first($field);
    }
}
