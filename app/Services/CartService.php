<?php

namespace App\Services;

use App\Core\Database;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repo\CartRepository;
use App\Repo\ProductsRepo;
use App\Validators\CartValidator;
use App\Repo\OrderRepository;
use App\Core\Logger;
use PDO;
use Exception;

class CartService 
{
    private PDO $pdo;
    public function __construct
    (
        private CartRepository $cartRepository,
        private ProductsRepo $productsRepo,
        private OrderRepository $orderRepository,
    ) {
      $this->pdo = Database::getInstance()->getConnection();
    }
   public function addProduct(int $productId,int $quantity = 1): void
   {
      $userId = getUserId();
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
      $item = $this->cartRepository->findItemByProductId($cart->id,$productId);
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
      $item->unit_price = $product->sale_price;

      $this->cartRepository->addItem($item);
      
   }
   public function getItems():array
   {
      $userId = getUserId();
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
   public function updateQuantity(int $itemId,int $quantity):void
   {
      $userId = getUserId();
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
   public function removeItem(int $itemId):void
   {
      $userId = getUserId();
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
   public function checkout(){
      $userId = getUserId();
      return CartValidator::validateCart($userId,$this->cartRepository,$this->productsRepo);
   }
   public function placeOrder(array $shippingInfo) : Order
   {
      $userId = getUserId();
      $errors = $this->checkout();      
      if(!empty($errors)){         
         throw new Exception(implode(' | ', $errors));
      }
      $cart = $this->cartRepository->getActiveCartByUserId($userId);
      $items = $this->cartRepository->getItems($userId);
      $subtotal = $this->getSubtotal($items);
      $discount = 0;
      $shippingCost=0;
      $totalPrice = $subtotal - $discount + $shippingCost;
      $this->pdo->beginTransaction();
      try{
         $order = new Order();
         $order->order_number    = $this->orderRepository->generateOrderNumber();
         $order->user_id         = $userId;
         $order->subtotal        = $subtotal;
         $order->discount        = $discount;
         $order->shipping_cost   = $shippingCost;
         $order->total_price     = $totalPrice;
         $order->address         = $shippingInfo['address'];
         $order->receiver_name   = $shippingInfo['receiver_name'];
         $order->receiver_mobile = $shippingInfo['receiver_mobile'];         
         $order = $this->orderRepository->create($order);   
         foreach ($items as $cartItem) {
               $orderItem = new OrderItem();
               $orderItem->order_id      = $order->id;
               $orderItem->product_id    = $cartItem->product_id;
               $orderItem->product_title = $cartItem->title;
               $orderItem->quantity      = $cartItem->quantity;
               $orderItem->unit_price    = $cartItem->unit_price;
               $orderItem->total_price   = $cartItem->quantity * $cartItem->unit_price;

               $this->orderRepository->addItem($orderItem);

               $stockDecreased = $this->productsRepo->decreaseStock($cartItem->product_id, $cartItem->quantity);

               if (!$stockDecreased) {
                  throw new Exception("موجودی «{$cartItem->title}» در لحظه‌ی ثبت سفارش کافی نبود");
               }            
         }
         $this->cartRepository->markAsOrdered($cart->id);
         $this->pdo->commit();
         return $order;
      }catch (Exception $e) {
            $this->pdo->rollBack();
            Logger::exception($e, ['location' => 'CartService::placeOrder']);
            throw $e;
        }
   }
   
}