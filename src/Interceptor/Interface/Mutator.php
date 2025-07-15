<?php

namespace Toniette\Interceptor\Interface;

interface Mutator
{
    public function mutate(mixed $subject): mixed;
}