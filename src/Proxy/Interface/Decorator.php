<?php

namespace Toniette\Proxy\Interface;

interface Decorator
{
    public function decorate(mixed $subject): mixed;
}