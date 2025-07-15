<?php

namespace Toniette\Interceptor;

use Attribute;
use Toniette\Interceptor\Interface\Interceptor;

#[Attribute(Attribute::TARGET_PROPERTY)]
class PropertyInterceptor implements Interceptor
{
    public function before(): void
    {
        echo __CLASS__ . " before property accessed.\n";
    }

    public function after(): void
    {
        echo __CLASS__ . " after property accessed.\n";
    }
}