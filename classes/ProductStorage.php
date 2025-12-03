<?php

class ProductStorage {
  private $file;

  public function __construct($file = null)
  {

    if ($file === null)
    {
      $file = __DIR__ . '/../data/products.json';
    }

    $this->file = $file;

    if (!file_exists($file))
    {
      file_put_contents($this->file, json_encode([]));
    }
  }

  private function loadProducts()
  {
    $json = file_get_contents($this->file);

    $data = json_decode($json, true);
    if (!is_array($data))
    {
      return [];
    }
    return $data;
  }

  private function saveProducts($products)
  {
    file_put_contents($this->file, json_encode($products, JSON_PRETTY_PRINT));
  }

  public function addProduct(Product $product)
  {
    $products = $this->loadProducts();

    if(empty($products))
    {
      $id = 1;
    }else {
      $last = end($products);
      $id = $last['id'] + 1;
    }

    $products[] = [
      "id" => $id,
      "name" => $product->getName(),
      "price" => $product->getPrice(),
      "stock" => $product->getStock(),
      "imgUrl" => $product->getImg()

    ];

    $this->saveProducts($products);
  }

}

