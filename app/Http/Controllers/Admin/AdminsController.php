<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ResponseCode;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminsController extends CController
{
    /**
     * 控制器初始化...
     */
    public function __construct()
    {
        // 登录验证
        $this->middleware("auth:{$this->guard}");

        // 权限验证中间件
        $this->middleware("admin_permissions");
    }

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
            'username' => 'required',
            'password' => 'required',
            'password2' => 'required',
        ]);

        $result = services()->adminService->create($request);
        if (!$result) {
            return $this->fail(ResponseCode::FAIL, '管理员账号添加失败...');
        }

        return $this->success([], '管理员账号添加成功...');
    }

    /**
     * 修改指定管理员登录密码
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
     * 获取管理员列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function lists(Request $request)
    {
        $this->validate($request, [
            'page' => 'required|integer:min:1',
            'page_size' => 'required|in:10,20,30,50,100',
        ]);

        $result = services()->adminService->getAdmins($request);
        return $this->success($result);
    }
}
