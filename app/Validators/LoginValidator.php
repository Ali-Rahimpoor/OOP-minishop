<?php
namespace App\Validators;
class LoginValidator
{
   public static function validate(array $data):array
   {
      $erros = [];
      $username = trim($data['username' ?? '']);
      $password = $data['password'] ?? '';
      if($username === ''){
         $erros['username'] = 'نام کاربری الزامی است';
      }
      if($password === ''){
         $erros['password'] = 'رمز عبور الزامی است';
      }
      return $erros;
   }
}