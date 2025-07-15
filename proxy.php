<?php

use Toniette\Proxy\Accessor\PropertyAccessor;
use Toniette\Proxy\AttributeAwareObjectProxy;
use Toniette\Proxy\Decorator\ClassDecorator;
use Toniette\Proxy\Decorator\MethodDecorator;
use Toniette\Proxy\Interceptor\ClassInterceptor;
use Toniette\Proxy\Interceptor\MethodInterceptor;
use Toniette\Proxy\Interceptor\PropertyInterceptor;
use Toniette\Proxy\Mutator\PropertyMutator;

require __DIR__ . '/vendor/autoload.php';

#[ClassInterceptor]
#[ClassDecorator]
class Subject {
    #[PropertyInterceptor]
    #[PropertyAccessor]
    #[PropertyMutator]
    public string $property {
        get {
            echo "Getting property value" . PHP_EOL;
            return $this->property;
        }
        set {
            echo "Setting property" . PHP_EOL;
            $this->property = $value;
        }
    }

    #[MethodInterceptor]
    #[MethodDecorator]
    public function method()
    {
        return "Method called";
    }

//    #[MethodInterceptor]
//    public static function staticMethod() //
//    {
//        echo "Static method called" . PHP_EOL;
//    }
}

$proxy = AttributeAwareObjectProxy::of(Subject::class);

var_dump($proxy->method());

$proxy->property = "Initial value";

var_dump($proxy->property);

$proxy->property = "New value";

var_dump($proxy->property);
