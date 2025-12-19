<?php

require_once __DIR__ . '/../classes/TransactionHistory.php';

$history = new TransactionHistory();
header("Content-Type: application/json");
echo json_encode($history->getTransaction(), JSON_PRETTY_PRINT);