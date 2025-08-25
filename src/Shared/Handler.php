<?php

namespace Toniette\Shared;

use ReflectionAttribute;
use ReflectionClassConstant;
use ReflectionEnum;
use RuntimeException;
use Toniette\Proxy\Interface\Decorator;
use Toniette\Proxy\Interface\Interceptor;
use Toniette\StateMachine\State;
use Toniette\StateMachine\Transition;

class Handler
{
    public static function handle(Transition $transition, callable $callback): State
    {
        $constant = self::getMatchingConstant($transition);

        if (!$constant) {
            return $callback();
        }

        $attributes = $constant->getAttributes();
        $interceptors = self::getInterceptors(...$attributes);
        $decorators = self::getDecorators(...$attributes);

        self::applyBeforeInterceptors(...$interceptors);

        $result = self::applyDecorators($callback(), ...$decorators);

        self::applyAfterInterceptors(...$interceptors);

        return $result;
    }

    private static function getMatchingConstant($transition): ?ReflectionClassConstant
    {
        $stateClassReflection = new ReflectionEnum($transition->targetState);
        $transitionConstants = $stateClassReflection->getReflectionConstants();
        $matchingConstants = array_filter(
            $transitionConstants,
            fn(ReflectionClassConstant $constant) => $constant->getValue() === $transition->name
        );

        if (count($matchingConstants) === 0) {
            return null;
        }

        if (count($matchingConstants) > 1) {
            throw new RuntimeException(
                "Multiple constants found for transition '$transition->name' in state '$transition->targetState'"
            );
        }

        return reset($matchingConstants);
    }

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
}