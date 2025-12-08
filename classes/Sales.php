<?php

class Sales {
  private $salesPerDay = [];
  private $file;

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
  }
  protected function loadSales()
  {
    $json = file_get_contents($this->file);

    $data = json_decode($json, true);

    if(!is_array($data))
    {
      return [];
    }
    return $data;
  }

  public function getSalesPerDay()
  {
    $sales = $this->loadSales();

    foreach ($sales as $sale)
    {
      $date = substr($sale['date'],0,10);

      if(!isset($this->salesPerDay[$date]))
      {
        $this->salesPerDay[$date] = 0;
      }
      $this->salesPerDay[$date] += $sale['total'];
    }

    $chartData = [];

    foreach($this->salesPerDay as $date => $totalSales)
    {
      $chartData[] = [
        "date" => $date,
        "total_sales" => $totalSales
      ];
    }


    return $chartData;
  }
}