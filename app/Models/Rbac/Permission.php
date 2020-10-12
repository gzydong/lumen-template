<?php

namespace App\Models\Rbac;

use App\Models\BaseModel;

/**
 * App\Models\Rbac\Permission
 *
 * @property int $id
 * @property string $route 权限名
 * @property string|null $display_name 权限显示名称
 * @property string|null $description 权限描述
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Permission extends BaseModel
{
    /**
     * @var string 定义表名字
     */
    protected $table = 'permissions';

    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }
}
