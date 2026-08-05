<?php
namespace App\Validators;

use App\Repo\CartRepository;
use App\Repo\ProductsRepo;
class CartValidator
{
   public static function validateCart(int $userId,CartRepository $cartRepo,ProductsRepo $productsRepo):array
   {            
      $errors = [];
      $items = $cartRepo->getItems($userId);
      if (empty($items)) {
            $errors[] = 'سبد خرید شما خالی است';
            return $errors;
        }

      foreach ($items as $item) {
         $product = $productsRepo->findById($item->product_id);

         if (!$product) {
               $errors[] = "محصول «{$item->title}» دیگر وجود ندارد و باید از سبد حذف شود";
               continue;
         }

         if ($product->status !== 'publish') {
               $errors[] = "محصول «{$item->title}» دیگر قابل فروش نیست و باید از سبد حذف شود";
               continue;
         }

         if ($item->quantity > $product->stock) {
               $errors[] = "موجودی «{$item->title}» کافی نیست. موجودی فعلی: {$product->stock}، تعداد در سبد شما: {$item->quantity}";
         }
      }

      return $errors;
   }
}