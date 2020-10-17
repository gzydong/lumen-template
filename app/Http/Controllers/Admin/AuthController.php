<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ResponseCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Admin
 */
class AuthController extends CController
{
    public function __construct()
    {
        // 授权中间件
        $this->middleware("auth:{$this->guard}", [
            'except' => ['login', 'logout']
        ]);
    }

    /**
     * 登录接口
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        // 请求数据验证
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        // 处理其它登录业务逻辑
        $admin = services()->adminService->login($request->only(['username', 'password']));

        // 通过用户信息换取用户token
        if (!$admin || !$token = auth($this->guard)->login($admin)) {
            return $this->fail(ResponseCode::AUTH_LOGON_FAIL, '账号不存在或密码填写错误...');
        }

        // 更新登录信息
        $admin->last_login_time = date('Y-m-d H:i:s');
        $admin->last_login_ip = $request->getClientIp();
        $admin->save();

        return $this->success([
            'auth' => $this->formatToken($token),
            'admin_info' => [
                'username' => $admin->username,
                'email' => $admin->email,
                'avatar' => $admin->avatar,
            ]
        ]);
    }

    /**
     * 退出登录接口
     *
     * @return JsonResponse
     */
    public function logout()
    {
        if ($this->isLogin()) {
            auth($this->guard)->logout();
        }

        return $this->success([], 'Successfully logged out');
    }

    /**
     * 刷新授权Token
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->success($this->formatToken(auth($this->guard)->refresh()));
    }

    /**
     * 格式话Token数据
     *
     * @param string $token 授权token
     * @return array
     */
    protected function formatToken($token)
    {
        $ttl = auth($this->guard)->factory()->getTTL();
        $expires_time = time() + $ttl * 60;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_time' => date('Y-m-d H:i:s', $expires_time)
        ];
    }
}
