<?php

namespace Toniette\Proxy\Interface;

interface Accessor
{
    public function access(mixed $subject): mixed;
}