<?php

namespace Toniette\Support;

use Toniette\Support\Interface\State;

class StateCollection extends Collection
{
    protected ?string $type = State::class;
}