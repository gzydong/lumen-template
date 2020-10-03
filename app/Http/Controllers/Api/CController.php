<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use App\Traits\JsonResponse;
use App\Exceptions\Code;
use \Illuminate\Contracts\Auth\Authenticatable;

class CController extends Controller
{
    use JsonResponse;

    /**
     * 授权方式
     *
     * @var string
     */
    protected $guard = 'api';

    /**
     * 自定义失败的验证响应
     *
     * @param Request $request
     * @param array $errors
     * @return mixed
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if (isset(static::$responseBuilder)) {
            return call_user_func(static::$responseBuilder, $request, $errors);
        }

        return $this->fail(Code::VALIDATION, array_shift($errors)[0], 422);
    }

    /**
     * 判断当前请求用户是否登录
     *
     * @return bool
     */
    protected function isLogin()
    {
        return auth($this->guard)->guest() === false;
    }

    /**
     * 获取登录用户信息
     *
     * @return Authenticatable|null
     */
    protected function user()
    {
        return auth($this->guard)->user();
    }
}
