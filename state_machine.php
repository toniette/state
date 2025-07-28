<?php

use Toniette\StateMachine\StatefulFactory;
use Toniette\StateMachine\Transaction;

require __DIR__ . '/vendor/autoload.php';

$transaction = StatefulFactory::create(
    Transaction::class,
    id: '12345',
    amount: 100
);

$transaction->sendToAnalysis();
$transaction->approve();
