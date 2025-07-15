<?php

namespace Toniette\Interceptor\Interface;

interface Accessor
{
    public function access(mixed $subject): mixed;
}