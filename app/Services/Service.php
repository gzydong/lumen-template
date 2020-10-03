<?php

namespace App\Services;

use Illuminate\Container\Container;

/**
 * 服务处理层
 *
 * Class Service
 * @package App\Services
 */
final class Service
{
    /**
     * 服务列表
     *
     * @var array
     */
    public $childService = [

    ];

    /**
     * Service constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param $attr
     * @return mixed|object
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __get($attr)
    {
        if (!isset($this->childService[$attr])) {
            throw new \InvalidArgumentException('Child Service [' . $attr . '] is not find in ' . get_called_class() . ', you must config it! ');
        }

        if (!Container::getInstance()->has($this->childService[$attr])) {
            $className = $this->childService[$attr];

            Container::getInstance()->singleton($className, function () use ($className) {
                return new $className();
            });
        }

        return Container::getInstance()->make($this->childService[$attr],[]);
    }
}
