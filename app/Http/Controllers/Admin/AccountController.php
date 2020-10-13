<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ResponseCode;
use App\Models\Admin;
use Illuminate\Http\Request;

class AccountController extends CController
{
    /**
     * 添加管理员账号
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'username' => 'require',
            'password' => 'require',
            'password2' => 'require',
        ]);

        $result = services()->adminService->create($request);
        if (!$result) {
            return $this->fail(ResponseCode::FAIL, '管理员账号添加失败...');
        }

        return $this->success([], '管理员账号添加成功...');
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
     * 修改管理员账户状态
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateStatus(Request $request)
    {
        $this->validate($request, [
            'admin_id' => 'required|integer:min:1',
            'status' => 'required|in:0,1,2',
        ]);

        // 状态映射
        $arr = [
            '0' => Admin::STATUS_ENABLES,
            '1' => Admin::STATUS_DISABLES,
            '2' => Admin::STATUS_DELETE
        ];

        $result = services()->adminService->updateStatus($request->input('admin_id'), $arr[$request->input('status')]);
        if (!$result) {
            $this->fail(ResponseCode::FAIL, '管理员状态修改失败');
        }

        return $this->success([], '管理员状态修改成功...');
    }

    /**
     * 修改登录账号相关信息
     *
     * @param Request $request
     */
    public function updateAccount(Request $request)
    {
        $this->validate($request, [
            'email' => 'present|email',
        ]);
    }
}
