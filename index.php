<?php

use Toniette\Support\StatefulFactory;
use Toniette\Transaction;

require __DIR__ . '/vendor/autoload.php';

/** @var Transaction $transaction */
$transaction = StatefulFactory::create(
    Transaction::class,
    id: '12345',
    amount: 100
);

$transaction->sendToAnalysis();
$transaction->approve();
