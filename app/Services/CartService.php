<?php

namespace App\Services;

use App\Models\CartItem;
use App\Repo\CartRepository;
use App\Repo\ProductsRepo;
use Exception;

class CartService 
{
    public function __construct(
        private CartRepository $cartRepository,
        private ProductsRepo $productsRepo
    ) {
    }
    public function addProduct(int $userId,int $productId,int $quantity = 1): void
   {
      
      if($quantity <= 0){
         throw new Exception('تعداد باید بیشتر از صفر باشه');
      }
      $product = $this->productsRepo->findById($productId);
      if(!$product){
         throw new Exception('محصولی پیدا نشد');
      }
      if ($product->status !== 'publish') {
            throw new Exception('این محصول در حال حاضر قابل خرید نیست');
      }
      $cart = $this->cartRepository->getActiveCartByUserId($userId);
      if(!$cart){
         $cart = $this->cartRepository->create($userId);
      }
      $item = $this->cartRepository->findItem($cart->id,$productId);
      $finalQuantity = $item ? ($item->quantity + $quantity) : $quantity;
      if($finalQuantity > $product->stock){
         throw new Exception('موجودی کافی نیست',$product->stock);
      }
      if($item){
         $item->quantity = $finalQuantity;
         $this->cartRepository->updateItem($item);
         return;
      }
      $item = new CartItem();
      $item->cart_id = $cart->id;
      $item->product_id = $product->id;
      $item->quantity = $quantity;
      $item->unit_price = $product->price;

      $this->cartRepository->addItem($item);
      
   }
   public function getItems(int $userId):array
   {
      return $this->cartRepository->getItems($userId);
   }
   public function getSubtotal(array $items):int
   {
      $subtotal = 0;
      foreach($items as $item){
         $subtotal += $item->quantity * $item->unit_price;
      }
      return $subtotal;
   }
   public function updateQuantity(int $userId,int $itemId,int $quantity):void
   {
      $cart = $this->cartRepository->getActiveCartByUserId($userId);
      if (!$cart) {
            throw new Exception('سبد خریدی برای شما پیدا نشد');
      }
      $item = $this->cartRepository->findItemById($itemId, $cart->id);

      if (!$item) {
         throw new Exception('این آیتم در سبد خرید شما پیدا نشد');
      }

      if ($quantity <= 0) {
         $this->cartRepository->deleteItem($item->id);
         return;
      }

      $product = $this->productsRepo->findById($item->product_id);

      if (!$product) {
         throw new Exception('محصول مربوط به این آیتم دیگر وجود ندارد');
      }

      if ($quantity > $product->stock) {
         throw new Exception('موجودی کافی نیست. حداکثر موجودی: ' . $product->stock);
      }

      $item->quantity = $quantity;
      $this->cartRepository->updateItem($item);
   }
   public function removeItem(int $userId,int $itemId):void
   {
      $cart = $this->cartRepository->getActiveCartByUserId($userId);

      if (!$cart) {
         throw new Exception('سبد خریدی برای شما پیدا نشد');
      }

      $item = $this->cartRepository->findItemById($itemId, $cart->id);

      if (!$item) {
         throw new Exception('این آیتم در سبد خرید شما پیدا نشد');
      }

      $this->cartRepository->deleteItem($item->id);
   }
   public function validateCart(int $userId):array
   {
      $errors = [];
      $items = $this->cartRepository->getItems($userId);
      if (empty($items)) {
            $errors[] = 'سبد خرید شما خالی است';
            return $errors;
        }

      foreach ($items as $item) {
         $product = $this->productsRepo->findById($item->product_id);

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