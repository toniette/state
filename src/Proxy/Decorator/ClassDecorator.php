<?php

namespace Toniette\Proxy\Decorator;

use Attribute;
use DateTimeImmutable;
use Toniette\Proxy\Interface\Decorator;

#[Attribute(Attribute::TARGET_CLASS)]
class ClassDecorator implements Decorator
{
    public function decorate(mixed $subject): mixed
    {
        $subject->decorated = true;
        $subject->decoratedBy = static::class;
        $subject->decoratedAt = new DateTimeImmutable();
        return $subject;
    }
}