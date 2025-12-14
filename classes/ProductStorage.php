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
        "id"       => $id,
        "name"     => $product->getName(),
        "price"    => $product->getPrice(),
        "stock"    => $product->getStock(),
        "category" => $product->getCategory(),
        "status"   => $product->getStatus(),
        "img"      => $product->getImg(),
        "description"     => $product->getDescription()
    ];

    $this->saveProducts($products);
  }

  public function removeProductById($id)
  {
    $products = $this->loadProducts();

    foreach($products as $key => $item )
    {
      if($item['id'] === (int) $id){
        unset($products[$key]);
        break;
      }
    }

    $products = array_values($products);

    $this->saveProducts($products);
  }

  public function getAllProducts()
  {
    return $this->loadProducts();
  }

  public function updateProduct(Product $product)
  {
    $products = $this->loadProducts();

    foreach($products as $key => $p)
    {
      if($p['id'] == $product->getId())
      {
          $products[$key]['name'] = $product->getName();
          $products[$key]['price'] = $product->getPrice();
          $products[$key]['stock'] = $product->getStock();
          $products[$key]['category'] = $product->getCategory();
          $products[$key]['status'] = $product->getStatus();
          $products[$key]['img'] = $product->getImg();
          $products[$key]['description'] = $product->getDescription();

          break;
      }
    }

    $this->saveProducts($products);
  }

 
}

