<?php


class Product {
  private $id;
  private $name;
  private $price;
  private $stock;
  private $category;
  private $status;
  private $isActive;
  private $imgUrl;
  private $description;

  public function __construct($id = null, $name = null, $price = null, $stock = null, $category = null, $status = true, $isActive = true, $imgUrl = null, $description = null)
  {
    $this->id = $id;
    $this->name = $name;
    $this->price = (float)$price;
    $this->stock = (int)$stock;
    $this->category = $category;
    $this->status = (bool) $status;
    $this->isActive = (bool)$isActive;
    $this->imgUrl = $imgUrl;
    $this->description = $description;
  }

  public function setId($pId){$this->id = $pId;}

  public function setName($name){$this->name = $name;}

  public function setPrice($price){$this->price = $price;}

  public function setStock($stock){$this->stock = $stock;}

  public function setCategory($category){$this->category = $category;}

  public function setStatus($status){$this->status = $status;}

  public function setIsActive($isActive){$this->isActive = (bool)$isActive;}

  public function setImage($img){$this->imgUrl = $img;}

  public function setDescription($desc){$this->description = $desc;}

  public function getId(){return $this->id;}

  public function getName(){ return $this->name;}

  public function getPrice() {return $this->price;}

  public function getStock(){return $this->stock;}

  public function getCategory(){return $this->category;}

  public function getStatus(){return $this->status;}

  public function getIsActive(){return $this->isActive;}

  public function getImg(){return $this->imgUrl;}

  public function getDescription(){return $this->description;}

  public function reduceStock($quantity)
  {
    if ($this->stock >= 0)
    {
      $this->stock -= $quantity;
    }
  }

}

