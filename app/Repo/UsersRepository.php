<?php
namespace App\Repo;

use App\Core\Database;
use App\Models\User;
use PDO;

class UsersRepository
{
   private PDO $pdo;
   public function __construct()
   {
      $this->pdo = Database::getInstance()->getConnection();      
   }   
   public function create(array $data):?User
   {            
      $sql = "INSERT INTO users (username,password,role) VALUES (:username,:password,:role)";
      $stmt = $this->pdo->prepare($sql);
      $ok = $stmt->execute([
            'username'=>$data['username'],
            'password' => $data['password'],
            'role'     => $data['role'],
         ]);

      if (!$ok) {
         return null;
      }

      $id = (int) $this->pdo->lastInsertId();
      return $this->findById($id);
   }

   public function findById(int $id): ?User
   {      
      $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
      $stmt->execute(['id' => $id]);
      $row = $stmt->fetch();
      if (!$row) {
         return null;
      }
      return User::fromArray($row);
   }
   public function findByUsername(string $username): ?User
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