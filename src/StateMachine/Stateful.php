<?php

namespace Toniette\StateMachine;

use BadMethodCallException;
use Exception;
use ReflectionClass;
use ReflectionProperty;

trait Stateful
{
    final private function __construct() {}
    static private array $stateProperties;

    /**
     * @throws Exception
     */
    final public function __get(string $name): mixed
    {
        if (isset($this->{$name}) && $this->{$name} instanceof State) {
            return $this->{$name};
        }

        throw new Exception("Property $name is not a accessible.");
    }

    final public function __call(string $name, array $arguments): State
    {
        $reflection = new ReflectionClass($this);

        static::$stateProperties ??= array_filter(
            $reflection->getProperties(),
            fn(ReflectionProperty $property) => is_subclass_of($property->getType()->getName(), State::class)
        );

        if (empty(static::$stateProperties)) {
            throw new BadMethodCallException("No state properties found in " . static::class);
        }

        foreach (static::$stateProperties as $property) {
            $state = $this->{$property->getName()};

            $transition = $state->allowedTransitions()->getByName($name);
            if ($transition === null) {
                continue;
            }

            $availableTransitions[$property->getName()] = $transition;
        }

        if (empty($availableTransitions)) {
            throw new BadMethodCallException(
                "The $name transition is not available for " . static::class
            );
        }

        if (count($availableTransitions) > 1) {
            throw new BadMethodCallException(
                "Multiple available transitions with name $name found in " . static::class
            );
        }

        $propertyName = key($availableTransitions);
        $transition = reset($availableTransitions);

        return $this->{$propertyName} = $transition->targetState;
    }
}