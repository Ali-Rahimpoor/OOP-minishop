<?php

namespace App\Services;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\CartItem;
use App\Repo\CartRepository;
use App\Repo\ProductsRepo;
use Exception;

class CartService extends Controller
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
      $cart = $this->cartRepository->getActiveCartByUserId($userId);
      if(!$cart){
         $cart = $this->cartRepository->create($userId);
      }
      $item = $this->cartRepository->findItem($cart->id,$productId);
      if($item){
         $item->quantity += $quantity;
         $this->cartRepository->updateItem($item);
         return ;
      }
      $item = new CartItem();
      $item->cart_id = $cart->id;
      $item -> product_id = $product->id;
      $item->quantity = $quantity;
      $item->unit_price = $product->price;
      $this->cartRepository->addItem($item);
      
   }
   public function getItems(int $userId):array
   {
      return $this->cartRepository->getItems($userId);
   }
   public function index():void
   {
      $items = $this->cartRepository->getItems(Auth::user_id());
      $this->view('cart/index',compact('items'));
   }
}