<?php

class Order {
  private $cart;
  private $payment;

  public function __construct(Cart $cart, Payment $payment)
  {
    $this->cart = $cart;
    $this->payment = $payment;
  }

  public function checkout() {
    $this->cart->computeTotal();
    $this->payment->process();

  }
}