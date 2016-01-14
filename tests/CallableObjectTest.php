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

        $this->assertSame('Tests\Ecailles\CallableObject\testFunctionWithParameters', $callable->get());
    }

    public function testGetShouldReturnTheRawCallableOfTheClosure()
    {
        $closure  = function () {
        };
        $callable = new CallableObject($closure);

        $this->assertSame($closure, $callable->get());
    }

    public function testGetShouldReturnTheRawCallableOfInstanceMethod()
    {
        $testClass = new TestClass();
        $callable  = new CallableObject([$testClass, 'instanceMethodWithParameters']);

        $this->assertSame([$testClass, 'instanceMethodWithParameters'], $callable->get());
    }

    public function testGetShouldReturnTheRawCallableOfMagicMethod()
    {
        $testClass  = new TestClass();
        $callable   = new CallableObject([$testClass, 'unknownMethod']);
        $directCall = new CallableObject([$testClass, '__call']);

        $this->assertSame([$testClass, 'unknownMethod'], $callable->get());
        $this->assertSame([$testClass, '__call'], $directCall->get());
    }

    public function testGetShouldReturnTheRawCallableOfClassMethodThatIsRepresentedAsAnArray()
    {
        $callable = new CallableObject(['Tests\Ecailles\CallableObject\TestClass', 'classMethodWithParameters']);

        $this->assertSame(['Tests\Ecailles\CallableObject\TestClass', 'classMethodWithParameters'], $callable->get());
    }

    public function testGetShouldReturnTheRawCallableOfClassMethodThatIsRepresentedAsAString()
    {
        $callable = new CallableObject('Tests\Ecailles\CallableObject\TestClass::classMethodWithParameters');

        $this->assertSame(['Tests\Ecailles\CallableObject\TestClass', 'classMethodWithParameters'], $callable->get());
    }

    public function testGetShouldReturnTheRawCallableOfCallStaticMethodThatIsRepresentedAsAnArray()
    {
        $callable   = new CallableObject(['Tests\Ecailles\CallableObject\TestClass', 'unknownMethod']);
        $directCall = new CallableObject(['Tests\Ecailles\CallableObject\TestClass', '__callStatic']);

        $this->assertSame(['Tests\Ecailles\CallableObject\TestClass', 'unknownMethod'], $callable->get());
        $this->assertSame(['Tests\Ecailles\CallableObject\TestClass', '__callStatic'], $directCall->get());
    }

    public function testGetShouldReturnTheRawCallableOfCallStaticMethodThatIsRepresentedAsAString()
    {
        $callable   = new CallableObject('Tests\Ecailles\CallableObject\TestClass::unknownMethod');
        $directCall = new CallableObject('Tests\Ecailles\CallableObject\TestClass::__callStatic');

        $this->assertSame(['Tests\Ecailles\CallableObject\TestClass', 'unknownMethod'], $callable->get());
        $this->assertSame(['Tests\Ecailles\CallableObject\TestClass', '__callStatic'], $directCall->get());
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

    public function testIsInstanceMethodShouldReturnTrueForMagicMethod()
    {
        $testClass  = new TestClass();
        $callable   = new CallableObject([$testClass, 'unknownMethod']);
        $directCall = new CallableObject([$testClass, '__call']);

        $this->assertFalse($callable->isFunction());
        $this->assertFalse($callable->isClosure());
        $this->assertTrue($callable->isInstanceMethod());
        $this->assertFalse($callable->isClassMethod());

        $this->assertFalse($directCall->isFunction());
        $this->assertFalse($directCall->isClosure());
        $this->assertTrue($directCall->isInstanceMethod());
        $this->assertFalse($directCall->isClassMethod());
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

    public function testIsClassMethodShouldReturnTrueForCallStaticMethodThatIsRepresentedAsAnArray()
    {
        $callable   = new CallableObject(['Tests\Ecailles\CallableObject\TestClass', 'unknownMethod']);
        $directCall = new CallableObject(['Tests\Ecailles\CallableObject\TestClass', '__callStatic']);

        $this->assertFalse($callable->isFunction());
        $this->assertFalse($callable->isClosure());
        $this->assertFalse($callable->isInstanceMethod());
        $this->assertTrue($callable->isClassMethod());

        $this->assertFalse($directCall->isFunction());
        $this->assertFalse($directCall->isClosure());
        $this->assertFalse($directCall->isInstanceMethod());
        $this->assertTrue($directCall->isClassMethod());
    }

    public function testIsClassMethodShouldReturnTrueForCallStaticMethodThatIsRepresentedAsAString()
    {
        $callable   = new CallableObject('Tests\Ecailles\CallableObject\TestClass::unknownMethod');
        $directCall = new CallableObject('Tests\Ecailles\CallableObject\TestClass::__callStatic');

        $this->assertFalse($callable->isFunction());
        $this->assertFalse($callable->isClosure());
        $this->assertFalse($callable->isInstanceMethod());
        $this->assertTrue($callable->isClassMethod());

        $this->assertFalse($directCall->isFunction());
        $this->assertFalse($directCall->isClosure());
        $this->assertFalse($directCall->isInstanceMethod());
        $this->assertTrue($directCall->isClassMethod());
    }

    public function testFunctionShouldBeCallable()
    {
        $callableWithParameters    = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithParameters');
        $callableWithoutParameters = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithoutParameters');

        $this->assertSame([1, 2], $callableWithParameters(1, 2));
        $this->assertSame(null, $callableWithoutParameters());
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

        $this->assertSame([1, 2], $callableWithParameters(1, 2));
        $this->assertSame(null, $callableWithoutParameters());
    }

    public function testInstanceMethodShouldBeCallable()
    {
        $testClass                 = new TestClass();
        $callableWithParameters    = new CallableObject([$testClass, 'instanceMethodWithParameters']);
        $callableWithoutParameters = new CallableObject([$testClass, 'instanceMethodWithoutParameters']);

        $this->assertSame([1, 2], $callableWithParameters(1, 2));
        $this->assertSame(null, $callableWithoutParameters());
    }

    public function testMagicMethodShouldBeCallable()
    {
        $testClass  = new TestClass();
        $callable   = new CallableObject([$testClass, 'unknownMethod']);
        $directCall = new CallableObject([$testClass, '__call']);

        $this->assertSame('unknownMethod', $callable(1, 2));
        $this->assertSame('unknownMethod', $callable());

        $this->assertSame('name', $directCall('name', []));
    }

    public function testClassMethodThatIsRepresentedAsAnArrayShouldBeCallable()
    {
        $callableWithParameters    = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'classMethodWithParameters',
            ]
        );
        $callableWithoutParameters = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'classMethodWithoutParameters',
            ]
        );

        $this->assertSame([1, 2], $callableWithParameters(1, 2));
        $this->assertSame(null, $callableWithoutParameters());
    }

    public function testClassMethodThatIsRepresentedAsAStringShouldBeCallable()
    {
        $callableWithParameters    = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::classMethodWithParameters'
        );
        $callableWithoutParameters = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::classMethodWithoutParameters'
        );

        $this->assertSame([1, 2], $callableWithParameters(1, 2));
        $this->assertSame(null, $callableWithoutParameters());
    }

    public function testCallStaticMethodThatIsRepresentedAsAnArrayShouldBeCallable()
    {
        $callable   = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'unknownMethod',
            ]
        );
        $directCall = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                '__callStatic',
            ]
        );

        $this->assertSame('unknownMethod', $callable(1, 2));
        $this->assertSame('unknownMethod', $callable());

        $this->assertSame('name', $directCall('name', []));
        $this->assertSame('name', $directCall('name', []));
    }

    public function testCallStaticMethodThatIsRepresentedAsAStringShouldBeCallable()
    {
        $callable   = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::unknownMethod'
        );
        $directCall = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::__callStatic'
        );

        $this->assertSame('unknownMethod', $callable(1, 2));
        $this->assertSame('unknownMethod', $callable());

        $this->assertSame('name', $directCall('name', []));
        $this->assertSame('name', $directCall('name', []));
    }

    public function testFunctionShouldBeCallableWithInvoke()
    {
        $callableWithParameters    = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithParameters');
        $callableWithoutParameters = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithoutParameters');

        $this->assertSame([1, 2], $callableWithParameters->invoke(1, 2));
        $this->assertSame(null, $callableWithoutParameters->invoke());
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

        $this->assertSame([1, 2], $callableWithParameters->invoke(1, 2));
        $this->assertSame(null, $callableWithoutParameters->invoke());
    }

    public function testInstanceMethodShouldBeCallableWithInvoke()
    {
        $testClass                 = new TestClass();
        $callableWithParameters    = new CallableObject([$testClass, 'instanceMethodWithParameters']);
        $callableWithoutParameters = new CallableObject([$testClass, 'instanceMethodWithoutParameters']);

        $this->assertSame([1, 2], $callableWithParameters->invoke(1, 2));
        $this->assertSame(null, $callableWithoutParameters->invoke());
    }

    public function testMagicMethodShouldBeCallableWithInvoke()
    {
        $testClass  = new TestClass();
        $callable   = new CallableObject([$testClass, 'unknownMethod']);
        $directCall = new CallableObject([$testClass, '__call']);

        $this->assertSame('unknownMethod', $callable->invoke(1, 2));
        $this->assertSame('unknownMethod', $callable->invoke());

        $this->assertSame('name', $directCall->invoke('name', []));
    }

    public function testClassMethodThatIsRepresentedAsAnArrayShouldBeCallableWithInvoke()
    {
        $callableWithParameters    = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'classMethodWithParameters',
            ]
        );
        $callableWithoutParameters = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'classMethodWithoutParameters',
            ]
        );

        $this->assertSame([1, 2], $callableWithParameters->invoke(1, 2));
        $this->assertSame(null, $callableWithoutParameters->invoke());
    }

    public function testClassMethodThatIsRepresentedAsAStringShouldBeCallableWithInvoke()
    {
        $callableWithParameters    = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::classMethodWithParameters'
        );
        $callableWithoutParameters = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::classMethodWithoutParameters'
        );

        $this->assertSame([1, 2], $callableWithParameters->invoke(1, 2));
        $this->assertSame(null, $callableWithoutParameters->invoke());
    }

    public function testCallStaticMethodThatIsRepresentedAsAnArrayShouldBeCallableWithInvoke()
    {
        $callable   = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'unknownMethod',
            ]
        );
        $directCall = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                '__callStatic',
            ]
        );

        $this->assertSame('unknownMethod', $callable->invoke(1, 2));
        $this->assertSame('unknownMethod', $callable->invoke());

        $this->assertSame('name', $directCall->invoke('name', []));
        $this->assertSame('name', $directCall->invoke('name', []));
    }

    public function testCallStaticMethodThatIsRepresentedAsAStringShouldBeCallableWithInvoke()
    {
        $callable   = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::unknownMethod'
        );
        $directCall = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::__callStatic'
        );

        $this->assertSame('unknownMethod', $callable->invoke(1, 2));
        $this->assertSame('unknownMethod', $callable->invoke());

        $this->assertSame('name', $directCall->invoke('name', []));
        $this->assertSame('name', $directCall->invoke('name', []));
    }

    public function testFunctionShouldBeCallableWithInvokeArgs()
    {
        $callableWithParameters    = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithParameters');
        $callableWithoutParameters = new CallableObject('Tests\Ecailles\CallableObject\testFunctionWithoutParameters');

        $this->assertSame([1, 2], $callableWithParameters->invokeArgs([1, 2]));
        $this->assertSame(null, $callableWithoutParameters->invokeArgs());
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

        $this->assertSame([1, 2], $callableWithParameters->invokeArgs([1, 2]));
        $this->assertSame(null, $callableWithoutParameters->invokeArgs());
    }

    public function testInstanceMethodShouldBeCallableWithInvokeArgs()
    {
        $testClass                 = new TestClass();
        $callableWithParameters    = new CallableObject([$testClass, 'instanceMethodWithParameters']);
        $callableWithoutParameters = new CallableObject([$testClass, 'instanceMethodWithoutParameters']);

        $this->assertSame([1, 2], $callableWithParameters->invokeArgs([1, 2]));
        $this->assertSame(null, $callableWithoutParameters->invokeArgs());
    }

    public function testMagicMethodShouldBeCallableWithInvokeArgs()
    {
        $testClass  = new TestClass();
        $callable   = new CallableObject([$testClass, 'unknownMethod']);
        $directCall = new CallableObject([$testClass, '__call']);

        $this->assertSame('unknownMethod', $callable->invokeArgs([1, 2]));
        $this->assertSame('unknownMethod', $callable->invokeArgs());

        $this->assertSame('name', $directCall->invokeArgs(['name', []]));
    }

    public function testClassMethodThatIsRepresentedAsAnArrayShouldBeCallableWithInvokeArgs()
    {
        $callableWithParameters    = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'classMethodWithParameters',
            ]
        );
        $callableWithoutParameters = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'classMethodWithoutParameters',
            ]
        );

        $this->assertSame([1, 2], $callableWithParameters->invokeArgs([1, 2]));
        $this->assertSame(null, $callableWithoutParameters->invokeArgs());
    }

    public function testClassMethodThatIsRepresentedAsAStringShouldBeCallableWithInvokeArgs()
    {
        $callableWithParameters    = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::classMethodWithParameters'
        );
        $callableWithoutParameters = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::classMethodWithoutParameters'
        );

        $this->assertSame([1, 2], $callableWithParameters->invokeArgs([1, 2]));
        $this->assertSame(null, $callableWithoutParameters->invokeArgs());
    }

    public function testCallStaticMethodThatIsRepresentedAsAnArrayShouldBeCallableWithInvokeArgs()
    {
        $callable   = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                'unknownMethod',
            ]
        );
        $directCall = new CallableObject(
            [
                'Tests\Ecailles\CallableObject\TestClass',
                '__callStatic',
            ]
        );

        $this->assertSame('unknownMethod', $callable->invokeArgs([1, 2]));
        $this->assertSame('unknownMethod', $callable->invokeArgs());

        $this->assertSame('name', $directCall->invokeArgs(['name', []]));
        $this->assertSame('name', $directCall->invokeArgs(['name', []]));
    }

    public function testCallStaticThatIsRepresentedAsAStringShouldBeCallableWithInvokeArgs()
    {
        $callable   = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::unknownMethod'
        );
        $directCall = new CallableObject(
            'Tests\Ecailles\CallableObject\TestClass::__callStatic'
        );

        $this->assertSame('unknownMethod', $callable->invokeArgs([1, 2]));
        $this->assertSame('unknownMethod', $callable->invokeArgs());

        $this->assertSame('name', $directCall->invokeArgs(['name', []]));
        $this->assertSame('name', $directCall->invokeArgs(['name', []]));
    }
}
