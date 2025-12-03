<?php

abstract class Payment {
  private $amount;

  public function __construct($amount)
  {
      $this->amount = $amount;
  }

  abstract public function process();
}

class CashPayment extends Payment{

  public function __construct($amount)
  {
    parent::__construct($amount);
  }

  public function process()
  {
    return "Processing with Cash Payment.";
  }
}