<?php

namespace App\Services;

use App\Models\Admin;
use App\Repositorys\AdminRepository;
use App\Traits\PagingTrait;
use Illuminate\Http\Request;
use Exception;

class AdminService
{
    /**
     * @var AdminRepository
     */
    protected $adminRepository;

    use PagingTrait;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * 登录业务处理
     *
     * @param array $params 参数信息
     */
    public function login(array $params)
    {
        Admin::where('username', $params['username'])->update([
            'last_login_time' => date('Y-m-d H:i:s'),
            'last_login_ip' => request()->getClientIp(),
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
            $admin->last_login_time = date('Y-m-d H:i:s');
            $admin->created_at = date('Y-m-d H:i:s');
            $admin->updated_at = date('Y-m-d H:i:s');
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
        return (bool)Admin::where('id', $admin_id)->update(['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * 获取管理员列表
     *
     * @param Request $request
     * @return mixed
     */
    public function getAdmins(Request $request)
    {
        $params = [];

        $rows = $this->adminRepository->admins($params);

        $total = Admin::count();

        return $this->getPagingRows($rows, $total, $request->input('page',1), $request->input('page_size',10));
    }
}
