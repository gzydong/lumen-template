<?php


namespace App\Exceptions;


/**
 *
 * Class Code
 * @package App\Exceptions
 */
class ResponseCode
{
    const SUCCESS                = 200;   // 响应成功返回码
    const FAIL                   = 10001; // 处理失败返回码
    const AUTHORIZATION_FAIL     = 10002; // 授权认证失败
    const AUTHENTICATE_FAIL      = 10003; // 未授权
    const METHOD_NOT_ALLOW       = 10004; // 请求方式不正确
    const RESOURCE_NOT_FOUND     = 10005; // 资源找不到
    const VALIDATION             = 10006; // 数据验证失败
    const SYSTEM_ERROR           = 10009; // 系统错误
}
