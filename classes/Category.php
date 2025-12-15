<?php

class Category {
  private $file;

  public function __construct($file = null)
  { 

    if($file == null)
    {
      $file = __DIR__ . '/../data/categories.json';
    }

    $this->file = $file;

    if(!file_exists($file))
    {
      file_put_contents($this->file, json_encode([]));
    }
  }

  private function loadProducts()
  {
    $json = file_get_contents($this->file);

    $data = json_decode($json,true);

    if(!is_array($data))
    {
      return [];
    }
    return $data;
  }

  public function getCategories()
  {
    return $this->loadProducts();
  }
}