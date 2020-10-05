<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
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
            'except' => ['login']
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

        $credentials = $request->only(['username', 'password']);
        $credentials['status'] = Admin::STATUS_ENABLES;// 过滤账号状态

        // 获取登录 Token
        $token = auth($this->guard)->attempt($credentials);
        if (!$token) {
            return $this->fail(ResponseCode::AUTH_LOGON_FAIL, '账号不存在或密码填写错误...');
        }

        return $this->success([
            'Authentication' => $this->formatToken($token),
            'admin_info' => '',
        ]);
    }

    /**
     * 退出登录接口
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth($this->guard)->logout();
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
