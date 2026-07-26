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
   public function verifyPassword(string $plainPassword): bool
   {
      return password_verify($plainPassword, $this->password);
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