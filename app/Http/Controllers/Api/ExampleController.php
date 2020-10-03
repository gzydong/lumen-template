<?php

namespace App\Http\Controllers\Api;


class ExampleController extends CController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
        return $this->success();
    }
}
