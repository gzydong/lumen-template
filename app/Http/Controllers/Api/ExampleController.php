<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ResponseCode;
use App\Http\Validators\ExampleValidate;
use App\Services\UserService;
use Illuminate\Http\Request;

class ExampleController extends CController
{

    public function index(Request $request)
    {
        $this->validate($request, [
            'article_id' => 'required|Integer|min:0',
            'class_id' => 'required|Integer|min:0',
            'title' => 'required|max:255',
            'content' => 'required',
            'md_content' => 'required',
        ]);

        // 链式调用 UserService 服务层（实例化后保存在laravel 容器中）
        services()->userService->example();
    }

    /**
     * 手动依赖注入服务层及验证器
     *
     * @param UserService $userService
     * @param ExampleValidate $exampleValidate
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function example(UserService $userService, ExampleValidate $exampleValidate, Request $request)
    {
        if (!$exampleValidate->check($request->all())) {
            return $this->fail(ResponseCode::VALIDATION, $exampleValidate->getError());
        }

        // 手动依赖注入服务
        $userService->example();
    }
}
