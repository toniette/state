<?php

namespace Toniette\Proxy\Decorator;

use Attribute;
use Toniette\Proxy\Interface\Decorator;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class MethodDecorator implements Decorator
{
    public function decorate(mixed $subject): mixed
    {
        return "$subject (decorated by " . static::class . ")";
    }
}