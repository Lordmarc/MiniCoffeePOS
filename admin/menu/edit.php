<?php
session_start();
require_once __DIR__ . '/../../classes/ProductStorage.php';
require_once __DIR__ . '/../../classes/Product.php';



if($_SERVER['REQUEST_METHOD'] === "POST")
{
  $id = $_POST['id'];
  $name = $_POST['name'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $category = $_POST['category'];
  $status = $_POST['status'];
  $img = $_POST['img'];
  $desc = $_POST['desc'];


  $product = new Product($id, $name, $price, $stock, $category, $status, $img, $desc);

  $_SESSION['edit_product'] = $product;
  header("Location: editproduct.php");
  exit();
}