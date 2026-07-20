<?php
namespace App\Models;
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
      $user->id         = isset($row['id']) ? (int) $row['id'] : null;
      $user->username   = $row['username'] ?? '';
      $user->password   = $row['password'] ?? '';
      $user->role       = $row['role'] ?? 'user';
      $user->created_at = $row['created_at'] ?? null;

      return $user;
   }
}