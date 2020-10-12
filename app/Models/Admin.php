<?php

namespace App\Models;

use App\Models\Rbac\Role;
use App\Models\Rbac\RolePermission;
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

    // 管理员状态
    const STATUS_ENABLES = 10; // 正常状态
    const STATUS_DISABLES = 0; // 禁用状态
    const STATUS_DELETE = -1;  // 删除状态

    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_admin', 'admin_id', 'role_id');
    }

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
     * 获取状态信息
     *
     * @param int|null $status 状态值
     * @return array|string
     */
    public static function getStatus($status = null)
    {
        $arr = [
            self::STATUS_ENABLES => '正常状态',
            self::STATUS_DISABLES => '禁用状态',
            self::STATUS_DELETE => '删除状态'
        ];

        return $status == null ? $arr : (isset($arr[$status]) ? $arr[$status] : '');
    }

    /**
     * 检测管理员是否拥有指定角色权限
     *
     * @param string $role_name 角色名
     * @return bool
     */
    public function hasRole($role_name)
    {
        return $this->roles()->where('roles.name', $role_name)->exists();
    }

    /**
     * 检测管理员是否有权限
     *
     * @param string $route 路由地址
     * @return bool
     */
    public function hasPerm($route)
    {
        // 获取当前用户所有角色ID
        $role_ids = $this->roles()->pluck('id')->toArray();

        if (!$role_ids) return false;

        $permissions = RolePermission::join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
            ->whereIn('role_permission.role_id', $role_ids)
            ->pluck('permissions.route');

        foreach ($permissions as $permission) {
            if ($route == $permission) return true;
        }

        return false;
    }
}
