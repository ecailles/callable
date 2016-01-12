<?php
namespace Tests\Ecailles\CallableObject;

function testFunctionWithParameters($parameter1, $parameter2)
{
    return [$parameter1, $parameter2];
}

function testFunctionWithoutParameters()
{
}
