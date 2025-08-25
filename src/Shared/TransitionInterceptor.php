<?php

namespace Toniette\Shared;

use Attribute;
use Toniette\Proxy\Interface\Interceptor;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT | Attribute::IS_REPEATABLE)]
class TransitionInterceptor implements Interceptor
{
    public function before(): void
    {
        echo 'Intercepting transition... (before)';
    }

    public function after(): void
    {
        echo 'Intercepting transition... (after)';
    }
}