<?php


class TransactionHistory {
  private string $file;
  private array $transactions = [];

  public function __construct($file = null)
  {

    if($file === null)
    {
      $file = __DIR__ . '/../data/transactions.json';
    }

    $this->file = $file;

    if(!file_exists($file))
    {
      file_put_contents($this->file, json_encode([]));
    }
    $this->loadTransaction();

  }

  public function loadTransaction()
  {
    $json = file_get_contents($this->file);

    $data = json_decode($json, true);

    if(!is_array($data))
    {
      return [];
    }

    $this->transactions = $data;
    return $this->transactions;
  }

  public function saveTransaction()
  {
    file_put_contents($this->file, json_encode($this->transactions, JSON_PRETTY_PRINT));
  }

  public function addTransaction(Cart $cart,float $subtotal = 0, float $discount = 0, float $total = 0)
  {
    $id = count($this->transactions) + 1;

    $itemsArray = [];

    foreach ($cart->getItems() as $itemData)
    {
      $product = $itemData['product'];
      $quantity = $itemData['quantity'];
  

      $itemsArray[] = [
        "id" => $product->getId(),
        "name" => $product->getName(),
        "price" => $product->getPrice(),
        "quantity" => $quantity,
        "subtotal" => $product->getPrice() * $quantity,
 
      ];

      
    }
    $transactions = [
        "id" => $id,
        "date" => date("Y-m-d H:i:s"),
        "items" => $itemsArray,
        "subtotal" => $subtotal,
        "discount" => $discount,
        "total" => $total,
      ];

      $this->transactions[] = $transactions;

      $this->saveTransaction();

  }

  public function getTransaction()
  {
    return $this->transactions;
  }
}