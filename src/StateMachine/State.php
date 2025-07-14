<?php

namespace Toniette\StateMachine;

interface State
{
    public function allowedTransitions(): TransitionCollection;
}