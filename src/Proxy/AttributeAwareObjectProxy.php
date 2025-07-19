<?php

namespace Toniette\Proxy;

use BadMethodCallException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use Toniette\Proxy\Interface\Accessor;
use Toniette\Proxy\Interface\Decorator;
use Toniette\Proxy\Interface\Interceptor;
use Toniette\Proxy\Interface\Mutator;

class AttributeAwareObjectProxy
{
    private ReflectionClass $reflection;

    private function __construct(
        private readonly object $target
    ) {
        $this->reflection = new ReflectionClass($this->target);
    }

    /**
     * @template T of object
     * @param class-string<T>|T $target
     * @param mixed ...$params
     * @return AttributeAwareObjectProxy&T
     */
    public static function of(object|string $target, mixed ...$params): self
    {
        if (is_string($target)) {
            return self::buildFromClass($target, ...$params);
        }

        return new self($target);
    }

    /**
     * @throws ReflectionException
     */
    private static function buildFromClass(string $class, mixed ...$params): self
    {
        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new ReflectionException("Class $class is not instantiable.");
        }

        $attributes = $reflection->getAttributes();
        $interceptors = self::getInterceptors(...$attributes);
        $decorators = self::getDecorators(...$attributes);

        self::applyBeforeInterceptors(...$interceptors);

        $instance = $reflection->newInstanceArgs($params);

        self::applyAfterInterceptors(...$interceptors);

        $instance = self::applyDecorators($instance, ...$decorators);

        if (!$instance instanceof $class) {
            throw new ReflectionException("Instance of $class could not be created.");
        }

        return new self($instance);
    }

    // interceptors

    /**
     * @return (ReflectionAttribute&Interceptor)[]
     */
    private static function getInterceptors(ReflectionAttribute ...$attributes): array
    {
        return array_filter(
            array_map(fn(ReflectionAttribute $attribute) => $attribute->newInstance(), $attributes),
            fn($instance) => $instance instanceof Interceptor
        );
    }

    private static function applyBeforeInterceptors(Interceptor ...$interceptors): void
    {
        foreach ($interceptors as $interceptor) {
            $interceptor->before();
        }
    }

    private static function applyAfterInterceptors(Interceptor ...$interceptors): void
    {
        foreach ($interceptors as $interceptor) {
            $interceptor->after();
        }
    }

    // decorators

    /**
     * @return (ReflectionAttribute&Decorator)[]
     */
    private static function getDecorators(ReflectionAttribute ...$attributes): array
    {
        return array_filter(
            array_map(fn(ReflectionAttribute $attribute) => $attribute->newInstance(), $attributes),
            fn($instance) => $instance instanceof Decorator
        );
    }

    private static function applyDecorators(mixed $subject, Decorator ...$decorators): mixed
    {
        foreach ($decorators as $decorator) {
            $subject = $decorator->decorate($subject);
        }

        return $subject;
    }

    // accessors

    private static function getAccessors(ReflectionAttribute ...$attributes): array
    {
        return array_filter(
            array_map(fn(ReflectionAttribute $attribute) => $attribute->newInstance(), $attributes),
            fn($instance) => $instance instanceof Accessor
        );
    }

    private static function applyAccessors(mixed $subject, Accessor ...$accessors): mixed
    {
        foreach ($accessors as $accessor) {
            $subject = $accessor->access($subject);
        }

        return $subject;
    }

    // mutators

    private static function getMutators(ReflectionAttribute ...$attributes): array
    {
        return array_filter(
            array_map(fn(ReflectionAttribute $attribute) => $attribute->newInstance(), $attributes),
            fn($instance) => $instance instanceof Mutator
        );
    }

    private static function applyMutators(mixed $subject, Mutator ...$mutators): mixed
    {
        foreach ($mutators as $mutator) {
            $subject = $mutator->mutate($subject);
        }

        return $subject;
    }

    // proxy

    /**
     * @throws ReflectionException
     */
    public function __call(string $name, array $arguments)
    {
        $method = $this->reflection->getMethod($name);

        $interceptors = $this->getInterceptors(...$method->getAttributes());

        $this->applyBeforeInterceptors(...$interceptors);

        $subject = $this->target->{$name}(...$arguments);

        $this->applyAfterInterceptors(...$interceptors);

        $decorators = $this->getDecorators(...$method->getAttributes());
        return $this->applyDecorators($subject, ...$decorators);
    }

    /**
     * @throws ReflectionException
     */
    public function __get(string $name): mixed
    {
        $property = $this->reflection->getProperty($name);

        $interceptors = $this->getInterceptors(...$property->getAttributes());

        $this->applyBeforeInterceptors(...$interceptors);

        $subject = $this->target->{$name};

        $this->applyAfterInterceptors(...$interceptors);

        $accessors = $this->getAccessors(...$property->getAttributes());
        return $this->applyAccessors($subject, ...$accessors);
    }

    /**
     * @throws ReflectionException
     */
    public function __set(string $name, mixed $value): void
    {
        $property = $this->reflection->getProperty($name);

        $interceptors = $this->getInterceptors(...$property->getAttributes());

        $this->applyBeforeInterceptors(...$interceptors);

        $mutators = $this->getMutators(...$property->getAttributes());
        $value = $this->applyMutators($value, ...$mutators);

        $this->target->{$name} = $value;

        $this->applyAfterInterceptors(...$interceptors);
    }

    /**
     * @throws ReflectionException
     */
    public function __invoke()
    {
        $attributes = $this->reflection->getMethod('__invoke')->getAttributes();
        $interceptors = $this->getInterceptors(...$attributes);

        $this->applyBeforeInterceptors(...$interceptors);

        $subject = ($this->target)();

        $this->applyAfterInterceptors(...$interceptors);

        $decorators = $this->getDecorators(...$attributes);
        return $this->applyDecorators($subject, ...$decorators);
    }

    public static function __callStatic(string $name, array $arguments)
    {
        throw new BadMethodCallException('Static method calls are not supported on Proxy. Use an instance instead.');
    }
}