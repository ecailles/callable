<?php
namespace Tests\Ecailles\CallableObject;

class TestClass
{
    public static function __callStatic($name, $arguments)
    {
        return $name;
    }

    public static function classMethodWithParameters($parameter1, $parameter2)
    {
        return [$parameter1, $parameter2];
    }

    public static function classMethodWithoutParameters()
    {
    }

    public function __call($name, $arguments)
    {
        return $name;
    }

    public function instanceMethodWithParameters($parameter1, $parameter2)
    {
        return [$parameter1, $parameter2];
    }

    public function instanceMethodWithoutParameters()
    {
    }
}
