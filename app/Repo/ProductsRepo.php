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
   public function create(Product $product):bool
   {
      $sql = "
         INSERT INTO products(
            title,
            description,
            thumbnail,
            price,
            sale_price,
            stock,
            status
         )
            VALUES
            (
               :title,
               :description,
               :thumbnail,
               :price,
               :sale_price,
               :stock,
               :status
            )";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([
         ':title' => $product->title,
         ':description' => $product->description,
         ':thumbnail'   => $product->thumbnail,
         ':price'       =>$product->price,
         ':sale_price'  => $product->sale_price,
         ':stock'       => $product->stock,
         ':status'      => $product->status
      ]);
   }
   public function findById(int $id): ?Product
   {
      $stmt = $this->pdo->prepare(
         "SELECT * FROM products WHERE ID = :id"
      );
      $stmt->execute(['id'=>$id]);
      $product = $stmt->fetch();
      if(!$product){
         return null;
      }
      // print_r($product);exit;
      return Product::fromArray($product);
   }
   public function update(int $id,Product $product):bool
   {
      $sql = "UPDATE products SET
      title = :title,
      description = :description,
      thumbnail = :thumbnail,
      price = :price,
      sale_price = :sale_price
      WHERE ID = :id";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([
        'id'          => $id,
        'title'       => $product->title,
        'description' => $product->description,
        'thumbnail'   => $product->thumbnail,
        'price'       => $product->price,
        'sale_price'  => $product->sale_price,
      ]);
   }
}