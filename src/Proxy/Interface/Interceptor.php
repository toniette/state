<?php

namespace Toniette\Proxy\Interface;

interface Interceptor
{
    public function before(): void;
    public function after(): void;
}