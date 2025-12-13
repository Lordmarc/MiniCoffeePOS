<?php

require_once __DIR__  . '/../classes/ProductStorage.php';

if (!isset($_POST['id']))
{
  echo "No product ID provided";
  exit;
}

$id = (int) $_POST['id'];

$storage = new ProductStorage();

$storage->removeProductById($id);

echo "Product with ID $id deleted.";