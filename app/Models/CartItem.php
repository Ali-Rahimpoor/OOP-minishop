<?php

namespace App\Models;

class CartItem
{
    public ?int $id = null;

    public int $cart_id;

    public int $product_id;

    public int $quantity;

    public int $unit_price;

    public string $created_at;
}