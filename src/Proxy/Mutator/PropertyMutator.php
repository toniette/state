<?php

namespace Toniette\Proxy\Mutator;

use Attribute;
use Toniette\Proxy\Interface\Mutator;

#[Attribute(Attribute::TARGET_PROPERTY)]
class PropertyMutator implements Mutator
{
    public function mutate(mixed $subject): mixed
    {
        return $subject . " (mutated by " . static::class . ")";
    }
}