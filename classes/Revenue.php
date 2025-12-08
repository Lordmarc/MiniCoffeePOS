<?php

class Revenue {

  private $file;

  public function __construct($file = null)
  {

    if($file == null)
    {
      $file = __DIR__ . '/../data/transactions.json';
    }

    $this->file = $file;

    if(!file_exists($file))
    {
      file_put_contents($this->file, json_encode([]));
    }
  }

  public function loadData()
  {
    $json = file_get_contents($this->file);

    $data = json_decode($json, JSON_PRETTY_PRINT);

    if(!is_array($data))
    {
      return [];
    }
    return $data;
  }

  public function getRevenue()
  {
    $data = $this->loadData();
    $today = date('Y-m-d');
    
    $total = 0;

    foreach ($data as $sale)
    {
      $date = substr($sale['date'],0,10);

      if($date === $today)
      {
        $total += $sale['total'];
      }

    }

    return [
      "sales" => $today,
      "total_revenue" => $total
    ];
  }
}