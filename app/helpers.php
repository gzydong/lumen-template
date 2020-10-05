<?php

/*
|--------------------------------------------------------------------------
| Common function method
|--------------------------------------------------------------------------
*/

/**
 * 格式化查询时间
 *
 * @param float $seconds sql 查询时间
 * @return string
 */
function format_duration(float $seconds)
{
    if ($seconds < 0.001) {
        return round($seconds * 1000000) . 'μs';
    } elseif ($seconds < 1) {
        return round($seconds * 1000, 2) . 'ms';
    }

    return round($seconds, 2) . 's';
}

/**
 * 获取 Service 服务
 *
 * @return  \App\Services\Service
 */
function services()
{
    return app('services');
}

/**
 * 解密密码
 *
 * @param string $password    密码
 * @param string $private_key 私钥
 *
 * @return string
 */
function decrypt_password($password, $private_key)
{
    openssl_private_decrypt(base64_decode($password), $decrypt_password, $private_key);
    return $decrypt_password;
}
