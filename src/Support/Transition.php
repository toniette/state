<?php

namespace Toniette\Support;

use Toniette\Support\Interface\State;

final class Transition
{
    public function __construct(
        public string $name,
        public State $targetState
    ) {}
}