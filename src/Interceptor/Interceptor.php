<?php

namespace Toniette\Interceptor;

interface Interceptor
{
    public function interceptClassInstantiation();
    public function interceptMethodCall();
    public function interceptPropertyAccess();
    public function interceptPropertyAssignment();
    public function interceptStaticMethodCall();
    public function interceptStaticPropertyAccess();
    public function interceptStaticPropertyAssignment();
}