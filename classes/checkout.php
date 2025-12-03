<?php
// checkout.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

try {

    // --- Required Files ---
    $files = [
        'Product.php',
        'Cart.php',
        'Order.php',
        'Payment.php',
        'TransactionHistory.php'
    ];

    foreach ($files as $file) {
        if (!file_exists($file)) {
            throw new Exception("Missing required file: $file");
        }
        require_once $file;
    }

    // --- Read Request ---
    $raw = file_get_contents("php://input");
    if (!$raw) {
        throw new Exception("No input received.");
    }

    $cartData = json_decode($raw, true);
    if (!is_array($cartData)) {
        throw new Exception("Invalid JSON input.");
    }

    // --- Load product database ---
    $productFile = __DIR__ . '/../data/products.json';
    if (!file_exists($productFile)) {
        throw new Exception("Products file not found.");
    }

    $productsDB = json_decode(file_get_contents($productFile), true);
    if (!is_array($productsDB)) {
        throw new Exception("Products JSON corrupted.");
    }

    $cartItems = $cartData['items'];
    $subtotal = $cartData['subtotal'];
    $discount = $cartData['discount'];
    $total = $cartData['total'];

    $cart = new Cart();
    $trans = new TransactionHistory();

    // --- Add items to cart ---
    foreach ($cartItems as $item) {

        if (!isset($item['id']) || !isset($item['quantity'])) {
            throw new Exception("Invalid cart item format.");
        }

        $found = false;

        foreach ($productsDB as &$p) {

            if ((string)$p['id'] === (string)$item['id']) {

                $found = true;

                if ($item['quantity'] > $p['stock']) {
                    throw new Exception("Insufficient stock for: {$p['name']}");
                }

                // Build product object
                $product = new Product(
                    $p['id'],
                    $p['name'],
                    $p['price'],
                    $p['stock']
                );

                // Add item to cart
                $cart->addItem($product, $item['quantity']);

                // Deduct stock
                $p['stock'] -= $item['quantity'];
                break;
            }
        }

        if (!$found) {
            throw new Exception("Product ID {$item['id']} not found.");
        }
    }

    // --- Save stock ---
    file_put_contents($productFile, json_encode($productsDB, JSON_PRETTY_PRINT));

    // --- Process Order ---
    $payment = new CashPayment("Cash");
    $order = new Order($cart, $payment);
    $order->checkout();

    // --- Save Transaction ---
    $trans->addTransaction($cart, $subtotal, $discount, $total );

    echo json_encode([
        "success" => true,
        "items"   => $cart->displayItems(),
        
        "subtotal" => $subtotal,
        "discount" => $discount,
        "total"   => $total,
        "message" => "Checkout successful!"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "error"   => $e->getMessage()
    ]);
}
