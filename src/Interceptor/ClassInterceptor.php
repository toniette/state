<?php

namespace Toniette\Interceptor;

use Attribute;
use Toniette\Interceptor\Interface\Interceptor;

#[Attribute(Attribute::TARGET_CLASS)]
class ClassInterceptor implements Interceptor
{
    public function before(): void
    {
        echo __CLASS__ . " before method called.\n";
    }

    public function after(): void
    {
        echo __CLASS__ . " after method called.\n";
    }
}