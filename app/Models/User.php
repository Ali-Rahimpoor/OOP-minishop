<?php
namespace App\Models;

use App\Core\Database;

class User {
   public ?int $id = null;
   public string $username = '';
   public string $password = '';
   public string $role = 'user';
   public ?string $created_at = null;

   public function isAdmin():bool
   {
      return $this->role === "admin";
   }
   public static function create(array $data):?self{      
      $pdo = Database::getInstance()->getConnection();
      $sql = "INSERT INTO users (username,password,role) VALUES (:username,:password,:role)";
      $stmt = $pdo->prepare($sql);
      $ok = $stmt->execute([
            'username'=>$data['username'],
            'password' => $data['password'],
            'role'     => $data['role'],
         ]);

      if (!$ok) {
         return null;
      }

      $id = (int) $pdo->lastInsertId();
      return self::findById($id);
   }

   public static function findById(int $id): ?self
   {
      $pdo = Database::getInstance()->getConnection();
      $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
      $stmt->execute(['id' => $id]);
      $row = $stmt->fetch();
      if (!$row) {
         return null;
      }
      return self::fromArray($row);
   }
   public static function fromArray(array $row): User
   {
      $user = new User();
      $user->id         = isset($row['ID']) ? (int) $row['ID'] : null;
      $user->username   = $row['username'] ?? '';
      $user->password   = $row['password'] ?? '';
      $user->role       = $row['role'] ?? 'user';
      $user->created_at = $row['created_at'] ?? null;      
      return $user;  
   }
   public static function findByUsername(string $username): ?self
   {
      $pdo = Database::getInstance()->getConnection();
      $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
      $stmt -> execute(['username'=>$username]);
      $row = $stmt->fetch();
      if(!$row){
         return null;
      }
      return User::fromArray($row);

   }
}