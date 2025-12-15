<?php

session_start();
require_once __DIR__ . '/../../classes/ProductStorage.php';
require_once __DIR__ . '/../../classes/Product.php';
require_once __DIR__ . '/../../classes/ImageHandler.php';

$storage = new ProductStorage();
$imageHandler = new ImageHandler();

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $id = $_POST['id'];
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];
    $stock = $_POST['product_stock'];
    $category = $_POST['product_category'];
    $desc = $_POST['product_description'];
    $status = $_POST['status'];
    $currentImg = $_POST['current_image'];
    $itemPosVisible = $_POST['pos_visible'];

    if(isset($_FILES['product_image']) && $_FILES['product_image']['name'])
    {
      $img = $imageHandler->upload($_FILES['product_image']);
    }else{
      $img = $currentImg;
    }

    $product = new Product($id,$name, $price, $stock, $category, $status, $itemPosVisible, $img, $desc);
    $storage->updateProduct($product);

    header("Location: menu.php");
    exit();
}