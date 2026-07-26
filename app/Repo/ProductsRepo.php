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
      sale_price = :sale_price,
      stock = :stock,
      status  = :status
      WHERE ID = :id";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([
        'id'          => $id,
        'title'       => $product->title,
        'description' => $product->description,
        'thumbnail'   => $product->thumbnail,
        'price'       => $product->price,
        'sale_price'  => $product->sale_price,
        'status'      => $product->status,
        'stock'       => $product->stock
      ]);
   }
   public function delete(int $id):bool
   {
      $sql = "DELETE FROM products WHERE ID = :id";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute(['id'=>$id]);
   }
   public function filter(array $filters, bool $publicOnly = false, int $page = 1, int $perPage = 12): array
   {
      $where  = [];
      $params = [];

      if ($publicOnly) {
         $where[] = "status = 'publish'";
      } elseif (!empty($filters['status'])) {
         $where[]          = 'status = :status';
         $params['status'] = $filters['status'];
      }

      if (!empty($filters['search'])) {
         $where[]          = 'title LIKE :search';
         $params['search'] = '%' . $filters['search'] . '%';
      }

      if (!empty($filters['price_from']) && is_numeric($filters['price_from'])) {
         $where[]              = 'price >= :price_from';
         $params['price_from'] = (int) $filters['price_from'];
      }

      if (!empty($filters['price_to']) && is_numeric($filters['price_to'])) {
         $where[]            = 'price <= :price_to';
         $params['price_to'] = (int) $filters['price_to'];
      }

      $sql = "SELECT *,
         CASE
               WHEN sale_price > 0 AND sale_price < price THEN (price - sale_price)
               ELSE 0
         END AS discount_amount
      FROM products";

      if (!empty($where)) {
         $sql .= ' WHERE ' . implode(' AND ', $where);
      }

      $sortMap = [
         'title'    => 'title',
         'price'    => 'price',
         'discount' => 'discount_amount',
      ];

      $sortKey    = $filters['sort'] ?? '';
      $sortColumn = $sortMap[$sortKey] ?? 'created_at';
      $sortDir    = (($filters['dir'] ?? '') === 'asc') ? 'ASC' : 'DESC';

      $sql .= " ORDER BY {$sortColumn} {$sortDir}";

      
      $page    = max(1, $page); 
      $offset  = ($page - 1) * $perPage;

      $sql .= ' LIMIT :limit OFFSET :offset';

      $stmt = $this->pdo->prepare($sql);

      foreach ($params as $key => $value) {
         $stmt->bindValue(':' . $key, $value);
      }

      $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

      $stmt->execute();

      $products = [];
      foreach ($stmt->fetchAll() as $row) {
         $products[] = Product::fromArray($row);
      }

      return $products;
   }
   public function countFiltered(array $filters, bool $publicOnly = false): int
   {
      $where  = [];
      $params = [];

      if ($publicOnly) {
         $where[] = "status = 'publish'";
      } elseif (!empty($filters['status'])) {
         $where[]          = 'status = :status';
         $params['status'] = $filters['status'];
      }

      if (!empty($filters['search'])) {
         $where[]          = 'title LIKE :search';
         $params['search'] = '%' . $filters['search'] . '%';
      }

      if (!empty($filters['price_from']) && is_numeric($filters['price_from'])) {
         $where[]              = 'price >= :price_from';
         $params['price_from'] = (int) $filters['price_from'];
      }

      if (!empty($filters['price_to']) && is_numeric($filters['price_to'])) {
         $where[]            = 'price <= :price_to';
         $params['price_to'] = (int) $filters['price_to'];
      }

      $sql = 'SELECT COUNT(*) FROM products';

      if (!empty($where)) {
         $sql .= ' WHERE ' . implode(' AND ', $where);
      }

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);

      return (int) $stmt->fetchColumn();
   }
}