<?php
namespace App\Models;

class Cart
{
   public ?int $id = null;
   public int $user_id;
   public string $status;
   public string $created_at;
}