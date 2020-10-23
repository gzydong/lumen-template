<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

/**
 * 管理员个人账户控制器
 *
 * Class AccountController
 * @package App\Http\Controllers\Admin
 */
class AccountController extends CController
{
    /**
     * 控制器初始化...
     */
    public function __construct()
    {
        $this->middleware("auth:{$this->guard}");
    }

    /**
     * 获取登录用户信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(){
        $adminInfo = $this->user();

        return $this->success([
            'username'=>$adminInfo->username,
            'nickname'=>'',
            'email'=>$adminInfo->email,
            'avatar'=>$adminInfo->avatar,
            'profile'=>''
        ]);
    }

    /**
     * 修改当前登录账号密码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required',
            'password2' => 'required|same:password',
        ]);

        $admin = $this->user();
        $admin->password = $request->input('password');
        $admin->save();

        return $this->success([], '当前登录账号密码已修改...');
    }

    /**
     * 修改当前账号相关信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateAccount(Request $request)
    {
        $this->validate($request, [
            'email' => 'present|email',
            'avatar' => 'present|url',
        ]);

        $admin = $this->user();
        $admin->email = $request->input('email', '');
        $admin->avatar = $request->input('avatar', '');
        $admin->save();

        return $this->success([], '管理员信息修改成功...');
    }
}
