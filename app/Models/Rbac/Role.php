<?php

namespace App\Models\Rbac;

use App\Models\BaseModel;
use App\Models\User;

/**
 * App\Models\Rbac\Role
 *
 * @property int $id 角色ID
 * @property string $name 角色名
 * @property string|null $display_name 角色显示名称
 * @property string|null $description 角色描述
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Role extends BaseModel
{
    /**
     * @var string 定义表名字
     */
    protected $table = 'roles';

    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_admin', 'role_id', 'admin_id');
    }

    /**
     * Many-to-Many relations with the permission model.
     * Named "perms" for backwards compatibility. Also because "perms" is short and sweet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }
}
