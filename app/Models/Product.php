<?php
namespace App\Models;

class Product
{
    public ?int $id = null;
    public string $title = '';
    public string $description = '';
    public ?string $thumbnail = null;
    public int $price = 0;
    public int $sale_price = 0;
    public int $stock = 0;
    public string $status = '';
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public static function fromArray(array $row): self
    {
        $product = new self();
        $product->id = isset($row['ID']) ? (int) $row['ID'] : null;
        $product->title = $row['title'] ?? '';
        $product->description = $row['description'] ?? '';
        $product->thumbnail = $row['thumbnail'] ?? '';
        
        
        $product->price = isset($row['price']) ? (int) $row['price'] : 0;
        $product->sale_price = isset($row['sale_price']) ? (int) $row['sale_price'] : 0;
        $product->stock = isset($row['stock']) ? (int) $row['stock'] : 0;
        
        $product->status = $row['status'] ?? '';
        $product->created_at = $row['created_at'] ?? null;
        $product->updated_at = $row['updated_at'] ?? null;
        
        return $product;
    }

    public function toArray(): array
    {
        return [
            'ID' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'status' => $this->status,
            'stock' => $this->stock,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}