<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {
            $table->unsignedInteger('id', true)->comment('用户ID');
            $table->string('mobile', 11)->default('')->unique()->comment('手机号');
            $table->string('password', 100)->default('')->comment('登录密码');
            $table->string('nickname', 20)->default('')->comment('用户昵称');
            $table->string('avatar', 255)->default('')->comment('用户头像地址');
            $table->unsignedTinyInteger('gender')->default(0)->unsigned()->comment('用户性别[0:未知;1:男;2:女]');
            $table->integer('created_at')->default(0)->comment('注册时间');

            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
