<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class CController extends Controller
{
    /**
     * 授权守卫
     *
     * @var string
     */
    protected $guard = 'api';
}
