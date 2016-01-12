<?php
/**
 * CallableObject Test
 *
 * @author Whizark <contact@whizark.com>
 * @see http://whizark.com
 * @copyright Copyright (C) 2013 Whizark.
 * @license MIT
 */

namespace Tests\Ecailles\CallableObject;

use PHPUnit_Framework_TestCase;
use Ecailles\CallableObject\CallableObject;

require 'testFunction.php';

/**
 * Class CallableObjectTest
 *
 * @package Tests\Ecailles\CallableObject
 */
class CallableObjectTest extends PHPUnit_Framework_TestCase
{
    public function testGetShouldReturnTheRawCallableOfTheFunction()
    {
        $callable = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithParameters');

        $this->assertSame($callable->get(), 'Tests\Ecailles\CallableObject\testFunctionWithParameters');
    }

    public function testGetShouldReturnTheRawCallableOfTheClosure()
    {
        $closure  = function () {
        };
        $callable = new CallableObject($closure);

        $this->assertSame($callable->get(), $closure);
    }

    public function testGetShouldReturnTheRawCallableOfInstanceMethod()
    {
        $testClass = new TestClass();
        $callable  = new CallableObject([$testClass, 'instanceMethodWithParameters']);

        $this->assertSame($callable->get(), [$testClass, 'instanceMethodWithParameters']);
    }

    public function testGetShouldReturnTheRawCallableOfClassMethodThatIsRepresentedAsAnArray()
    {
        $callable = new CallableObject(['Tests\Ecailles\CallableObject\TestClass', 'classMethodWithParameters']);

        $this->assertSame($callable->get(), ['Tests\Ecailles\CallableObject\TestClass', 'classMethodWithParameters']);
    }

    public function testGetShouldReturnTheRawCallableOfClassMethodThatIsRepresentedAsAString()
    {
        $callable = new CallableObject('Tests\Ecailles\CallableObject\TestClass::classMethodWithParameters');

        $this->assertSame($callable->get(), ['Tests\Ecailles\CallableObject\TestClass', 'classMethodWithParameters']);
    }

    public function testIsFunctionShouldReturnTrueForFunction()
    {
        $callable = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithParameters');

        $this->assertTrue($callable->isFunction());
        $this->assertFalse($callable->isClosure());
        $this->assertFalse($callable->isInstanceMethod());
        $this->assertFalse($callable->isClassMethod());
    }

    public function testIsClosureShouldReturnTrueForClosure()
    {
        $callable = new CallableObject(
            function ($parameter1, $parameter2) {
            }
        );

        $this->assertFalse($callable->isFunction());
        $this->assertTrue($callable->isClosure());
        $this->assertFalse($callable->isInstanceMethod());
        $this->assertFalse($callable->isClassMethod());
    }

    public function testIsInstanceMethodShouldReturnTrueForInstanceMethod()
    {
        $testClass = new TestClass();
        $callable  = new CallableObject([$testClass, 'instanceMethodWithParameters']);

        $this->assertFalse($callable->isFunction());
        $this->assertFalse($callable->isClosure());
        $this->assertTrue($callable->isInstanceMethod());
        $this->assertFalse($callable->isClassMethod());
    }

    public function testIsClassMethodShouldReturnTrueForClassMethodThatIsRepresentedAsAnArray()
    {
        $callable = new CallableObject(['Tests\Ecailles\CallableObject\TestClass', 'classMethodWithParameters']);

        $this->assertFalse($callable->isFunction());
        $this->assertFalse($callable->isClosure());
        $this->assertFalse($callable->isInstanceMethod());
        $this->assertTrue($callable->isClassMethod());
    }

    public function testIsClassMethodShouldReturnTrueForClassMethodThatIsRepresentedAsAString()
    {
        $callable = new CallableObject('Tests\Ecailles\CallableObject\TestClass::classMethodWithParameters');

        $this->assertFalse($callable->isFunction());
        $this->assertFalse($callable->isClosure());
        $this->assertFalse($callable->isInstanceMethod());
        $this->assertTrue($callable->isClassMethod());
    }

    public function testFunctionShouldBeCallable()
    {
        $callableWithParameters    = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithParameters');
        $callableWithoutParameters = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithoutParameters');

        $this->assertSame($callableWithParameters(1, 2), [1, 2]);
        $this->assertSame($callableWithoutParameters(), null);
    }

    public function testClosureShouldBeCallable()
    {
        $callableWithParameters    = new CallableObject(
            function ($parameter1, $parameter2) {
                return [$parameter1, $parameter2];
            }
        );
        $callableWithoutParameters = new CallableObject(
            function () {
            }
        );

        $this->assertSame($callableWithParameters(1, 2), [1, 2]);
        $this->assertSame($callableWithoutParameters(), null);
    }

    public function testInstanceMethodShouldBeCallable()
    {
        $testClass                 = new TestClass();
        $callableWithParameters    = new CallableObject([$testClass, 'instanceMethodWithParameters']);
        $callableWithoutParameters = new CallableObject([$testClass, 'instanceMethodWithoutParameters']);

        $this->assertSame($callableWithParameters(1, 2), [1, 2]);
        $this->assertSame($callableWithoutParameters(), null);
    }

    public function testClassMethodThatIsRepresentedAsAnArrayShouldBeCallable()
    {
        $callableWithParameters    = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'classMethodWithParameters'
            ]
        );
        $callableWithoutParameters = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'classMethodWithoutParameters'
            ]
        );

        $this->assertSame($callableWithParameters(1, 2), [1, 2]);
        $this->assertSame($callableWithoutParameters(), null);
    }

    public function testClassMethodThatIsRepresentedAsAStringShouldBeCallable()
    {
        $callableWithParameters    = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::classMethodWithParameters'
        );
        $callableWithoutParameters = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::classMethodWithoutParameters'
        );

        $this->assertSame($callableWithParameters(1, 2), [1, 2]);
        $this->assertSame($callableWithoutParameters(), null);
    }

    public function testFunctionShouldBeCallableWithInvoke()
    {
        $callableWithParameters    = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithParameters');
        $callableWithoutParameters = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithoutParameters');

        $this->assertSame($callableWithParameters->invoke(1, 2), [1, 2]);
        $this->assertSame($callableWithoutParameters->invoke(), null);
    }

    public function testClosureShouldBeCallableWithInvoke()
    {
        $callableWithParameters    = new CallableObject(
            function ($parameter1, $parameter2) {
                return [$parameter1, $parameter2];
            }
        );
        $callableWithoutParameters = new CallableObject(
            function () {
            }
        );

        $this->assertSame($callableWithParameters->invoke(1, 2), [1, 2]);
        $this->assertSame($callableWithoutParameters->invoke(), null);
    }

    public function testInstanceMethodShouldBeCallableWithInvoke()
    {
        $testClass                 = new TestClass();
        $callableWithParameters    = new CallableObject([$testClass, 'instanceMethodWithParameters']);
        $callableWithoutParameters = new CallableObject([$testClass, 'instanceMethodWithoutParameters']);

        $this->assertSame($callableWithParameters->invoke(1, 2), [1, 2]);
        $this->assertSame($callableWithoutParameters->invoke(), null);
    }

    public function testClassMethodThatIsRepresentedAsAnArrayShouldBeCallableWithInvoke()
    {
        $callableWithParameters    = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'classMethodWithParameters'
            ]
        );
        $callableWithoutParameters = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'classMethodWithoutParameters'
            ]
        );

        $this->assertSame($callableWithParameters->invoke(1, 2), [1, 2]);
        $this->assertSame($callableWithoutParameters->invoke(), null);
    }

    public function testClassMethodThatIsRepresentedAsAStringShouldBeCallableWithInvoke()
    {
        $callableWithParameters    = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::classMethodWithParameters'
        );
        $callableWithoutParameters = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::classMethodWithoutParameters'
        );

        $this->assertSame($callableWithParameters->invoke(1, 2), [1, 2]);
        $this->assertSame($callableWithoutParameters->invoke(), null);
    }

    public function testFunctionShouldBeCallableWithInvokeArgs()
    {
        $callableWithParameters    = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithParameters');
        $callableWithoutParameters = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithoutParameters');

        $this->assertSame($callableWithParameters->invokeArgs([1, 2]), [1, 2]);
        $this->assertSame($callableWithoutParameters->invokeArgs(), null);
    }

    public function testClosureShouldBeCallableWithInvokeArgs()
    {
        $callableWithParameters    = new CallableObject(
            function ($parameter1, $parameter2) {
                return [$parameter1, $parameter2];
            }
        );
        $callableWithoutParameters = new CallableObject(
            function () {
            }
        );

        $this->assertSame($callableWithParameters->invokeArgs([1, 2]), [1, 2]);
        $this->assertSame($callableWithoutParameters->invokeArgs(), null);
    }

    public function testInstanceMethodShouldBeCallableWithInvokeArgs()
    {
        $testClass                 = new TestClass();
        $callableWithParameters    = new CallableObject([$testClass, 'instanceMethodWithParameters']);
        $callableWithoutParameters = new CallableObject([$testClass, 'instanceMethodWithoutParameters']);

        $this->assertSame($callableWithParameters->invokeArgs([1, 2]), [1, 2]);
        $this->assertSame($callableWithoutParameters->invokeArgs(), null);
    }

    public function testClassMethodThatIsRepresentedAsAnArrayShouldBeCallableWithInvokeArgs()
    {
        $callableWithParameters    = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'classMethodWithParameters'
            ]
        );
        $callableWithoutParameters = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'classMethodWithoutParameters'
            ]
        );

        $this->assertSame($callableWithParameters->invokeArgs([1, 2]), [1, 2]);
        $this->assertSame($callableWithoutParameters->invokeArgs(), null);
    }

    public function testClassMethodThatIsRepresentedAsAStringShouldBeCallableWithInvokeArgs()
    {
        $callableWithParameters    = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::classMethodWithParameters'
        );
        $callableWithoutParameters = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::classMethodWithoutParameters'
        );

        $this->assertSame($callableWithParameters->invokeArgs([1, 2]), [1, 2]);
        $this->assertSame($callableWithoutParameters->invokeArgs(), null);
    }
}
