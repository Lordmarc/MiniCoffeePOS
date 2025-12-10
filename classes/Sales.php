<?php

class Sales {
  private $salesPerDay = [];
  private $transactionFile;
  private $productFile;

  public function __construct($transactionFile = null, $productFile = null)
  {
    
    if($transactionFile === null)
    {
      $transactionFile = __DIR__ . '/../data/transactions.json';
    }
    if($productFile === null)
    {
      $productFile = __DIR__. '/../data/products.json';
    }

    $this->transactionFile = $transactionFile;
    $this->productFile = $productFile;

    if(!file_exists($transactionFile))
    {
      file_put_contents($this->transactionFile, json_encode([]));
    }

    if(!file_exists($productFile))
    {
      file_put_contents($this->productFile, json_encode([]));
    }
  }
  protected function loadSales()
  {
    $json = file_get_contents($this->transactionFile);

    $data = json_decode($json, true);

    if(!is_array($data))
    {
      return [];
    }
    return $data;
  }

  protected function loadProduct()
  {
    $products = file_get_contents($this->productFile);
    $data = json_decode($products, true);

    if(!is_array($data))
    {
      return [];
    }

    return $data;
  }

  public function getSalesPerDay()
  {
    $sales = $this->loadSales();
    $count = 0;

    $today = date('Y-m-d');
    foreach ($sales as $sale)
    {
      $date = substr($sale['date'],0,10);

      if(!isset($this->salesPerDay[$date]))
      {
        $this->salesPerDay[$date] = 0;
      }

      if($today == $date)
      {
      $count += 1;
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


    return [
      "count_order" => $count,
      "sales" => $chartData,
    ];
  }


  public function getTop3Items()
  {
    $sales = $this->loadSales();
    $totals = [];
    $today = date('Y-m-d');

    foreach ($sales as $sale)
    {
      $date = substr($sale['date'],0, 10);
      if($date == $today)
      {
        foreach($sale['items'] as $item)
      {
        $name = trim($item['name']);
        $qty = $item['quantity'];


        if(!isset($totals[$name]))
        {
          $totals[$name] = 0;
        }
        
        $totals[$name] += $qty;
      }
      }
      
    }

    $list = [];

    foreach($totals as $name => $totalQty)
    {
      $list[] = [
        "name" => $name,
        "total_sold" => $totalQty
      ];
    }

    usort($list, function ($a, $b) {
      return $b['total_sold'] - $a['total_sold'];
    });

    return array_slice($list, 0 ,3);
  }

  public function getItemSold()
  {
    $sales = $this->loadSales();

    $today = date('Y-m-d');

    $count = 0;

    foreach($sales as $sale)
    {
      $date = substr($sale['date'], 0, 10);
       if ($today == $date)
      {
          foreach($sale['items'] as $item )
          {
            $count += $item['quantity'];
          }
      } 
    
     
    }

    return $count;
  }

  public function getWeeklyBest()
  {
    $sales = $this->loadSales();
    $products = $this->loadProduct();

    $weeklyBestSeller = [];

    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
    $endOfWeek = date('Y-m-d', strtotime('sunday this week'));

    foreach($sales as $sale)
    {
      $date = substr($sale['date'],0,10);

      if($date >= $startOfWeek && $date <= $endOfWeek)
      {

        foreach($sale['items'] as $item)
        {
          $itemId = $item['id'];
          $itemName = $item['name'];
          $itemQty = $item['quantity'];
          $itemImg = "";

          foreach($products as $p)
          {
            if($itemId == $p['id'])
            {
              $img = $p['img'];

             $itemImg = $img;
            }
          }

          $weeklyBestSeller 
        }
      }
    }

    $weekly = [];

    foreach($weeklyBestSeller as $name => $image)
    {
      $weekly[] = [
        "name" => $name,
        "img" => $img
      ];
    }
  }
}