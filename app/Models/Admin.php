<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class Admin Model
 *
 * @property integer $id 管理ID
 * @property string $username 管理员登录账号/登录名
 * @property string $password 登录密码
 * @property integer $status 账号状态[-1:已删除;0:已禁用;10:正常;]
 * @property string $last_login_time 最后一次登录时间
 * @property string $last_login_ip 最后一次登录IP
 * @property integer $created_at 创建时间
 * @property integer $updated_at 更新时间
 *
 * @package App\Models
 */
class Admin extends BaseModel implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * @var string 定义表名字
     */
    protected $table = 'admins';

    /**
     * @var array 批量赋值的黑名单
     */
    protected $guarded = ['id'];

    /**
     * 账号状态:正常状态
     */
    const STATUS_ENABLES = 10;

    /**
     * 账号状态:禁用状态
     */
    const STATUS_DISABLES = 0;

    /**
     * 账号状态:删除状态
     */
    const STATUS_DELETE = -1;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 设置密码
     *
     * @param string $value 密码值
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = false;
}
