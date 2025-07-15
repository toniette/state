<?php

namespace Toniette\Proxy\Interface;

interface Mutator
{
    public function mutate(mixed $subject): mixed;
}