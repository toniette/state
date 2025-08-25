<?php

namespace Toniette\Proxy\Accessor;

use Attribute;
use Toniette\Proxy\Interface\Accessor;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class PropertyAccessor implements Accessor
{
    public function access(mixed $subject): mixed
    {
        return $subject . " (accessed by " . static::class . ")";
    }
}