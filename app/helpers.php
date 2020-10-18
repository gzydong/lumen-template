<?php

/*
|--------------------------------------------------------------------------
| Common function method
|--------------------------------------------------------------------------
*/

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
 * @param string $password 密码
 * @param string $private_key 私钥
 *
 * @return string
 */
function decrypt_password($password, $private_key)
{
    openssl_private_decrypt(base64_decode($password), $decrypt_password, $private_key);
    return $decrypt_password;
}

/**
 * 验证密码是否输入错误
 *
 * @param $password
 * @param $hash
 * @return mixed
 */
function check_password($password, $hash)
{
    return app('hash')->check($password, $hash);
}

function get_orderby_sort($sort)
{
    $arr = [
        'ascend' => 'asc',
        'descend' => 'desc'
    ];

    return isset($arr[$sort]) ? $arr[$sort] : null;
}
