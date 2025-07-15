<?php

namespace Toniette\Interceptor\Interface;

interface Interceptor
{
    public function before(): void;
    public function after(): void;
}