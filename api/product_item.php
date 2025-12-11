<?php

require_once __DIR__ . '/../classes/ProductStorage.php';

$products = new ProductStorage();
header('Content-Type: application/json');
echo json_encode($products->getAllProducts(), JSON_PRETTY_PRINT);