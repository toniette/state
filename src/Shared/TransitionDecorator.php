<?php

namespace Toniette\Shared;

use Attribute;
use Toniette\Proxy\Interface\Decorator;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT | Attribute::IS_REPEATABLE)]
class TransitionDecorator implements Decorator
{
    public function decorate(mixed $subject): mixed
    {
        echo 'Decorating transition...';
        return $subject;
    }
}