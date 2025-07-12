<?php

namespace Toniette\Support\Interface;

use Toniette\Support\TransitionCollection;

interface State
{
    public function allowedTransitions(): TransitionCollection;
}