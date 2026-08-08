<?php

namespace App\Models;

class OrderItem
{
    public ?int $id = null;

    public int $order_id;

    public int $product_id;

    public string $product_title;

    public int $quantity;

    public int $unit_price;

    public int $total_price;

    public ?string $created_at = null;
}