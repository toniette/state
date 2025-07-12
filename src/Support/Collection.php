<?php

namespace Toniette\Support;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use SplObjectStorage;

abstract class Collection extends SplObjectStorage
{
    protected ?string $type;

    public static string $namespace;

    /**
     * @throws ReflectionException
     */
    public function __construct(object ...$objects)
    {
        if (empty($this->type)) {
            $this->guessType();
        }

        $this->attachAll(...$objects);
    }

    public static function removeSuffix($className, string $suffix = 'Collection'): string
    {
        if (str_ends_with($className, $suffix)) {
            return substr($className, 0, -strlen($suffix));
        }
        return $className;
    }

    /**
     * @throws ReflectionException
     */
    private function getClassName(): string
    {
        return new ReflectionClass($this::class)->getShortName();
    }

    /**
     * @throws ReflectionException
     */
    private function getEntityName(): string
    {
        return self::removeSuffix($this->getClassName());
    }

    /**
     * @throws ReflectionException
     */
    private function guessType(): void
    {
        $className = $this->getEntityName();
        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class $className does not exist");
        }

        $this->type = $className;
    }

    public function attach(object $object, mixed $info = null): void
    {
        if (!$object instanceof $this->type) {
            throw new InvalidArgumentException(
                "Object must be an instance of $this->type, " . $object::class . " given"
            );
        }

        parent::attach($object, $info);
    }

    public function attachAll(object ...$objects): void
    {
        foreach ($objects as $object) {
            $this->attach($object);
        }
    }

    public function offsetSet($object, $info = null): void
    {
        $this->attach($object, $info);
    }

    public function addAll(SplObjectStorage $storage): void
    {
        foreach ($storage as $object) {
            $this->attach($object, $storage[$object]);
        }
    }

    public static function from(object ...$objects): static
    {
        return new static(...$objects);
    }

    public function toArray(): array
    {
        return iterator_to_array($this);
    }
}