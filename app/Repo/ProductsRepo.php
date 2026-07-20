<?php
namespace App\Repo;
use App\Models\Product;
use App\Core\Database;
use PDO;
class ProductsRepo
{
   private PDO $pdo;
   public function __construct()
   {
      $this->pdo = Database::getInstance()->getConnection();
   }
   public function latest(int $limit = 10):array
   {
      $sql = "SELECT * FROM products ORDER BY ID DESC LIMIT :limit";
      
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':limit',$limit,PDO::PARAM_INT);
      $stmt->execute();
      $products = [];
      $rows = $stmt->fetchAll();
      foreach($rows as $row){        
          $products[] = Product::fromArray($row);
      }      
      return $products;
   }
}