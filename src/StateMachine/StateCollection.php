<?php

namespace Toniette\StateMachine;

use Toniette\Collection;

class StateCollection extends Collection
{
    protected ?string $type = State::class;
}