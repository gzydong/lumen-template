<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CController extends Controller
{
    /**
     * 授权守卫
     *
     * @var string
     */
    protected $guard = 'admin';
}
