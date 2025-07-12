<?php

namespace Toniette;

use Toniette\Status\TransactionStatus;
use Toniette\Support\Trait\Stateful;

class Transaction
{
    use Stateful;

    protected TransactionStatus $state = TransactionStatus::PENDING;
    public string $id;
    public float $amount;
}