<?php

namespace App\Services;

use App\Models\User;
use App\Repositorys\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function example()
    {
        $user = $this->userRepository->findById(1);
    }

    /**
     * 用户注册
     *
     * @param Request $request
     * @return array
     */
    public function register(Request $request)
    {
        // 查询手机号是否已经被注册
        $result = $this->userRepository->findByMobile($request->input('mobile'),['id']);
        if($result){
            return [false,'手机号已被他(她)人注册使用...',[]];
        }

        $userModel = new User();
        $userModel->mobile = $request->input('mobile');
        $userModel->password = $request->input('password',Str::random(8));
        $userModel->nickname = $request->input('mobile');
        $userModel->created_at = time();
        $userModel->save();

        // ... 处理用户注册业务逻辑

        return [true,'手机号注册成功...',[
            'user'=>$userModel
        ]];
    }
}
