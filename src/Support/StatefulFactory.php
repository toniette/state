<?php

namespace Toniette\Support;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Toniette\Support\Interface\State;
use Toniette\Support\Trait\Stateful;

final class StatefulFactory
{
    private function __construct() {}

    public static function create(string $statefulClass, mixed ...$args): object
    {
        $reflection = new ReflectionClass($statefulClass);

        self::ensureClassIsStateful($reflection);
        self::ensureStatePropertiesAreNotPublic($reflection);
        self::ensureStatePropertiesHasInitialValue($reflection);

        $instance = $reflection->newInstanceWithoutConstructor();

        foreach ($args as $key => $value) {
            if (is_string($key)) {
                if (!$reflection->hasProperty($key)) {
                    throw new InvalidArgumentException("Property $key does not exist in $statefulClass.");
                }
                $instance->{$key} = $value;
            } else {
                throw new InvalidArgumentException("Arguments must be passed as key-value pairs.");
            }
        }

        return $instance;
    }

    private static function ensureStatePropertiesAreNotPublic(ReflectionClass $reflection): void
    {
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            if ($property->isPublic() && self::isState($property)) {
                throw new InvalidArgumentException(
                    "State class properties must not be public: {$property->getName()}"
                );
            }
        }
    }

    private static function ensureClassIsStateful(ReflectionClass $reflection): void
    {
        if (!in_array(Stateful::class, $reflection->getTraitNames())) {
            throw new InvalidArgumentException("Class must use the Stateful trait.");
        }
    }

    /**
     * @throws ReflectionException
     */
    private static function ensureStatePropertiesHasInitialValue(ReflectionClass $reflection): void
    {
        $properties = $reflection->getProperties();
        $instance = $reflection->newInstanceWithoutConstructor();

        foreach ($properties as $property) {
            if (!self::isState($property)) {
                continue;
            }

            if ($property->isStatic() || !$property->isInitialized($instance)) {
                throw new InvalidArgumentException("State properties must have an initial value.");
            }
        }
    }

    public static function isState(ReflectionProperty $property): bool
    {
        return $property->getDeclaringClass()->isSubclassOf(State::class);
    }
}