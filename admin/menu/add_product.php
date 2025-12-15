<?php

session_start();
require_once __DIR__ . '/../../classes/ProductStorage.php';
require_once __DIR__ . '/../../classes/Product.php';
require_once __DIR__ . '/../../classes/ImageHandler.php';


$storage = new ProductStorage();

$imageHandler = new ImageHandler();

if($_SERVER['REQUEST_METHOD'] === "POST")
{

  $name = $_POST['product_name'];
  $price = $_POST['product_price'];
  $stock = $_POST['product_stock'];
  $category = $_POST['product_category'];
  $desc = $_POST['product_description'];
  $status = $_POST['status'] ?? 1;

  $isActive = $_POST['isActive'] ?? 1;
  
  if(isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
    $img = $imageHandler->upload($_FILES['product_image']);
} else {
    $img = "";
}


  $product = new Product(null,$name, $price, $stock, $category, $status, $isActive, $img, $desc);
  
  $storage->addProduct($product);

  header("Location: menu.php");
  exit();
}