<?php
class Cart {
    private $items = [];
    public float $total = 0;
    public  $discount = 0;

    public function addItem(Product $product, $quantity)
    {
        $this->items[] = [
            "product" => $product,    // ✅ Store Product object
            "quantity" => $quantity
        ];
    }

    public function removeItem(Product $product)
    {
        foreach($this->items as $key => $item)
        {
            // ✅ Access through product object
            if($item['product']->getId() === $product->getId())
            {
                unset($this->items[$key]);
                break;
            }
        }
    }


    public function computeTotal(): float
    {
        $this->total = 0;
        foreach($this->items as $item)
        {
            // // ✅ CORRECT: Access price through product object
            $price = (float)$item['product']->getPrice();
            $quantity = (int)$item['quantity'];
            $this->total += $price  * $quantity ;
       
            
        }
        return $this->total;
    }

    public function displayItems()
    {
        $display = [];
        foreach($this->items as $item) {
            $display[] = [
                'name' => $item['product']->getName(),
                'price' => $item['product']->getPrice(),
                'quantity' => $item['quantity']
            ];
        }
        return $display;
    }



    public function getItems()
    {
        return $this->items;
    }

    public function getTotal()
    {
        return $this->total;
    }


}
?>