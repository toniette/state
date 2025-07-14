<?php

namespace Toniette\StateMachine;

class Transaction
{
    use Stateful;

    protected TransactionStatus $state = TransactionStatus::PENDING;
    public string $id;
    public float $amount;
}