<?php

namespace App\Repo;

use App\Core\Database;
use App\Models\Cart;
use App\Models\CartItem;
use PDO;

class CartRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }
    public function getActiveCartByUserId(int $userId): ?Cart
    {
      $sql = "SELECT * FROM carts WHERE user_id = :user_id AND status = 'active' LIMIT 1 ";
      $stmt = $this->pdo->prepare($sql);
      $stmt -> bindValue(':user_id',$userId,PDO::PARAM_INT);
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_CLASS,Cart::class);
      $cart = $stmt->fetch();
      return $cart ?: null;
    }
    public function create(int $userId): Cart
    {
      $sql = "
      INSERT INTO carts (user_id,status) VALUES (:user_id,'active')
      ";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':user_id',$userId,PDO::PARAM_INT);
      $stmt->execute();
      $cart = new Cart();
      $cart->id = (int)$this->pdo->lastInsertId();
      $cart->user_id = $userId;
      $cart->status = 'active';
      return $cart;
    }
    public function findItem (int $cartId,int $productId):?CartItem
    {
      $sql = "SELECT * FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id LIMIT 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt -> bindValue(':cart_id',$cartId,PDO::PARAM_INT);
      $stmt -> bindValue(':product_id',$productId,PDO::PARAM_INT);
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_CLASS,CartItem::class);
      $item = $stmt->fetch();
      return $item ?: null;
    }
    public function addItem(CartItem $item):bool
    {      
      $sql = "INSERT INTO cart_items (cart_id,product_id,quantity,unit_price) VALUES (:cart_id,:product_id,:quantity,:unit_price)";
      $stmt = $this->pdo->prepare($sql);
      
      $stmt->bindValue(':cart_id', $item->cart_id, PDO::PARAM_INT);
      $stmt->bindValue(':product_id', $item->product_id, PDO::PARAM_INT);
      $stmt->bindValue(':quantity', $item->quantity, PDO::PARAM_INT);
      $stmt->bindValue(':unit_price', $item->unit_price, PDO::PARAM_INT);
      
      
      return $stmt->execute();      
    }
    public function updateItem(CartItem $item): bool
    {
      $sql = "
          UPDATE cart_items
         SET quantity = :quantity
         WHERE id = :id
      ";

      $stmt = $this->pdo->prepare($sql);

      $stmt->bindValue(':quantity', $item->quantity, PDO::PARAM_INT);

      $stmt->bindValue(':id', $item->id, PDO::PARAM_INT);

      return $stmt->execute();
    }
    public function getItems(int $userId):array 
    {
     $sql = " SELECT           
          cart_items.id,
          cart_items.product_id,
          cart_items.quantity,
          cart_items.unit_price,
          products.title,
          products.thumbnail
          FROM carts 
          INNER JOIN cart_items
              ON carts.id = cart_items.cart_id
          INNER JOIN products
              ON products.id = cart_items.product_id
          WHERE 
              carts.user_id = :user_id
              AND carts.status = 'active'
          ORDER BY cart_items.id DESC;
      ";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':user_id',$userId,PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}