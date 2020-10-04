<?php

namespace App\Services;

use App\Repositorys\UserRepository;
use Illuminate\Http\Request;

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
     */
    public function register(Request $request)
    {
        // ... 处理用户注册业务逻辑
    }
}
