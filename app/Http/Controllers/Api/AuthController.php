<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ResponseCode;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends CController
{
    /**
     * 用户服务层
     *
     * @var UserService
     */
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        // 授权中间件
        $this->middleware("auth:{$this->guard}", [
            'except' => ['logins']
        ]);
    }

    /**
     * 登录接口
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // 数据验证
        $this->validate($request, ['username' => 'required', 'password' => 'required']);

        // 登录
        $token = auth($this->guard)->attempt($request->only(['username', 'password']));
        if (!$token) {
            return $this->fail(ResponseCode::AUTHORIZATION_FAIL, '账号不存在或密码填写错误...');
        }

        return $this->success([
            'Authentication' => $this->formatToken($token),
            'user_info' => '',
        ]);
    }

    /**
     * 注册接口
     *
     * @param Request $request
     */
    public function register(Request $request)
    {
        services()->userService->register($request);
    }

    /**
     * 退出接口
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth($this->guard)->logout();
        return $this->success([], 'Successfully logged out');
    }

    /**
     * 刷新授权token
     *
     * @return mixed
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
