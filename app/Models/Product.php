<?php
namespace App\Models;
class Product{
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
      $product->id               = isset($row['ID']) ? (int) $row['ID'] : null;
      $product->title            = $row['title'] ?? '';
      $product->description      = $row['description'] ?? '';
      $product->thumbnail        = $row['thumbnail'] ?? '';
      $product->price            = (int) $row['price'] ?? 0;
      $product->sale_price        = (int) $row['sale_price'] ?? 0;
      $product->status           = $row['status'] ?? '';
      $product->stock            = (int) $row['stock'] ?? 0;
      $product->created_at       = $row['created_at'] ?? null;
      $product->updated_at       = $row['updated_at'] ?? '';
      return $product;
   }
}