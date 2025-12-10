<?php

require_once  __DIR__ . '/../classes/Sales.php';

$topItems = new Sales();
header('Content-Type: application/json');
echo json_encode($topItems->getTop3Items(), JSON_PRETTY_PRINT);