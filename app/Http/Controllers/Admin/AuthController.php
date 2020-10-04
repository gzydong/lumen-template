<?php

namespace App\Http\Controllers\Admin;

/**
 * Class AuthController
 * @package App\Http\Controllers\Admin
 */
class AuthController extends CController
{
    public function __construct(){
        // 授权中间件
        $this->middleware("auth:{$this->guard}", [
            'except' => ['logins']
        ]);
    }

    public function login(){
        dd('ads');
    }

    public function logout(){

    }

    public function refresh(){

    }
}
