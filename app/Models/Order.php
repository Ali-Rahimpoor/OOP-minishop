<?php

namespace App\Models;

class Order
{
    public ?int $id = null;

    public int $order_number;

    public int $user_id;

    public int $subtotal;

    public int $discount = 0;

    public int $shipping_cost = 0;

    public int $total_price;

    public string $payment_status = 'pending';

    public string $order_status = 'processing';

    public string $address;

    public string $receiver_name;

    public string $receiver_mobile;

    public ?string $description = null;

    public ?string $created_at = null;
}