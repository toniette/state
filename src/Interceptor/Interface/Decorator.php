<?php

namespace Toniette\Interceptor\Interface;

interface Decorator
{
    public function decorate(mixed $subject): mixed;
}