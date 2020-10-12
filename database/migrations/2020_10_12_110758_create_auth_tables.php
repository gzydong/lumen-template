<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAuthTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rolesTable = 'roles';
        $roleUserTable = 'role_admin';
        $permissionsTable = 'permissions';
        $permissionRoleTable = 'role_permission';

        $userModel = new \App\Models\Admin();
        $userKeyName = $userModel->getKeyName();
        $usersTable = $userModel->getTable();
        $prefix = DB::getConfig('prefix');

        // Create table for storing roles
        Schema::create($rolesTable, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->comment('角色ID');
            $table->string('name')->unique()->comment('角色名');
            $table->string('display_name')->nullable()->comment('角色显示名称');
            $table->string('description')->nullable()->comment('角色描述');
            $table->timestamps();

            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `{$prefix}{$rolesTable}` comment 'RBAC - 角色表'");

        // Create table for associating roles to users (Many-to-Many)
        Schema::create($roleUserTable, function (Blueprint $table) use ($userKeyName, $rolesTable, $usersTable) {
            $table->engine = 'InnoDB';
            $table->bigInteger('admin_id')->unsigned()->comment('管理员用户ID');
            $table->integer('role_id')->unsigned()->comment('角色ID');

            $table->foreign('admin_id')->references($userKeyName)->on($usersTable)->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on($rolesTable)->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['admin_id', 'role_id']);

            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `{$prefix}{$roleUserTable}` comment 'RBAC - 角色、用户关联表'");

        // Create table for storing permissions
        Schema::create($permissionsTable, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('route')->unique()->comment('权限路由');
            $table->string('display_name')->nullable()->comment('权限显示名称');
            $table->string('description')->nullable()->comment('权限描述');
            $table->timestamps();

            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `{$prefix}{$permissionsTable}` comment 'RBAC - 权限表'");

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create($permissionRoleTable, function (Blueprint $table) use ($permissionsTable, $rolesTable) {
            $table->engine = 'InnoDB';
            $table->integer('permission_id')->unsigned()->comment('权限ID');
            $table->integer('role_id')->unsigned()->comment('角色ID');
            $table->foreign('permission_id')->references('id')->on($permissionsTable)->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on($rolesTable)->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);

            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->engine = 'InnoDB';
        });
        DB::statement("ALTER TABLE `{$prefix}{$permissionRoleTable}` comment 'RBAC - 角色、权限关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roles');
        Schema::drop('role_admin');
        Schema::drop('role_permission');
        Schema::drop('permissions');
    }
}
