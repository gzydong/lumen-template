<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Http\Request;
use Exception;

class AdminService
{
    /**
     * 登录业务处理
     *
     * @param array $params
     */
    public function login(array $params)
    {
        Admin::where('username', $params['username'])->update([
            'last_login_time' => time(),
            'last_login_ip' => request()->getClientIp(),
            'updated_at' => time()
        ]);
    }

    /**
     * 创建管理员账号
     *
     * @param Request $request
     * @return bool
     */
    public function create(Request $request)
    {
        try {
            $admin = new Admin();
            $admin->username = $request->input('username');
            $admin->password = $request->input('password');
            $admin->status = Admin::STATUS_ENABLES;
            $admin->created_at = time();
            $admin->updated_at = time();
            return $admin->save();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 修改管理员状态
     *
     * @param int $admin_id 管理员ID
     * @param int $status 账号状态
     * @return bool
     */
    public function updateStatus(int $admin_id, int $status)
    {
        return (bool)Admin::where('id', $admin_id)->update(['status' => $status, 'updated_at' => time()]);
    }
}
