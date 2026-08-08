<?php

namespace App\Repo;

use App\Core\Database;
use App\Models\Order;
use App\Models\OrderItem;
use PDO;

class OrderRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function create(Order $order): Order
    {
        $sql = "INSERT INTO orders
            (order_number, user_id, subtotal, discount, shipping_cost, total_price,
             payment_status, order_status, address, receiver_name, receiver_mobile, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $order->order_number,
            $order->user_id,
            $order->subtotal,
            $order->discount,
            $order->shipping_cost,
            $order->total_price,
            $order->payment_status,
            $order->order_status,
            $order->address,
            $order->receiver_name,
            $order->receiver_mobile,
            $order->description,
        ]);

        $order->id = (int) $this->pdo->lastInsertId();
        return $order;
    }

    public function addItem(OrderItem $item): void
    {
        $sql = "INSERT INTO order_items
            (order_id, product_id, product_title, quantity, unit_price, total_price)
            VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $item->order_id,
            $item->product_id,
            $item->product_title,
            $item->quantity,
            $item->unit_price,
            $item->total_price,
        ]);
    }

    public function logStatus(int $orderId, string $status, string $description, int $changedBy): void
    {
        $sql = "INSERT INTO order_status_log (order_id, status, description, changed_by) VALUES (?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$orderId, $status, $description, $changedBy]);
    }

    public function generateOrderNumber(): int
    {
        do {
            $number = (int) (date('ymd') . random_int(1000, 9999));
            $exists = $this->orderNumberExists($number);
        } while ($exists);

        return $number;
    }

    private function orderNumberExists(int $orderNumber): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM orders WHERE order_number = ? LIMIT 1");
        $stmt->execute([$orderNumber]);
        return (bool) $stmt->fetch();
    }
}