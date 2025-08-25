<?php

namespace Toniette\StateMachine;

use BackedEnum;

final class Transition
{
    public function __construct(
        public string           $name,
        public State&BackedEnum $targetState
    ) {}
}