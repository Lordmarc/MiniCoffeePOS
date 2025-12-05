<?php
require_once 'classes/Sales.php';
$sales = new Sales();
header('Content-Type: application/json');
echo json_encode($sales->getSalesPerDay(), JSON_PRETTY_PRINT);
