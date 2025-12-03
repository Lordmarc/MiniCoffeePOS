<?php


class Product {
  private $id;
  private $name;
  private $price;
  private $stock;
  private $imgUrl;

  public function __construct($id = null, $name = null, $price = null, $stock = null, $imgUrl = null)
  {
    $this->id = $id;
    $this->name = $name;
    $this->price = (float)$price;
    $this->stock = (int)$stock;
    $this->imgUrl = $imgUrl;
  }

  public function setId($pId)
  {
    $this->id = $pId;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function setPrice($price)
  {
    $this->price = $price;
  }

  public function setStock($stock)
  {
    $this->stock = $stock;
  }

  public function setImage($img)
  {
    $this->imgUrl = $img;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getPrice()
  {
     return $this->price;
  }

  public function getStock()
  {
    return $this->stock;
  }

  public function getImg()
  {
    return $this->imgUrl;
  }

  public function reduceStock($quantity)
  {
    if ($this->stock >= 0)
    {
      $this->stock -= $quantity;
    }
  }

}

