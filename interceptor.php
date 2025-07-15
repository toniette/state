<?php

use Toniette\Interceptor\AttributeAwareObjectProxy;
use Toniette\Interceptor\ClassInterceptor;
use Toniette\Interceptor\MethodInterceptor;
use Toniette\Interceptor\PropertyInterceptor;

require __DIR__ . '/vendor/autoload.php';

#[ClassInterceptor]
class Subject {
    #[PropertyInterceptor]
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
    public function method()
    {
        echo "Method called";
    }

    #[MethodInterceptor]
    public static function staticMethod()
    {
        echo "Static method called";
    }
}

$subject = new Subject();
$proxy = AttributeAwareObjectProxy::of($subject);