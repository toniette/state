<?php

namespace Toniette\StateMachine;

final class Transition
{
    public function __construct(
        public string $name,
        public State $targetState
    ) {}
}