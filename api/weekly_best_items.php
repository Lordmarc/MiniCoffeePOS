<?php

require_once  __DIR__ . '/../classes/Sales.php';
$sales= new Sales();
header('Content-Type: application/json');
echo json_encode($sales->getWeeklyBest(), JSON_PRETTY_PRINT);