<?php

namespace Toniette\StateMachine;

use Attribute;
use Toniette\Collection;

#[Attribute(Attribute::TARGET_ALL)]
class TransitionCollection extends Collection
{
    protected ?string $type = Transition::class;

    public function getByName(string $name): ?Transition
    {
        return array_find(iterator_to_array($this), fn(Transition $transition) => $transition->name === $name);
    }
}