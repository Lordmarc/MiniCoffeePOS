<?php

require_once __DIR__ . '/../classes/Revenue.php';

$rev = new Revenue();
header('Content-Type: application/json');
echo json_encode($rev->getRevenue(), JSON_PRETTY_PRINT);