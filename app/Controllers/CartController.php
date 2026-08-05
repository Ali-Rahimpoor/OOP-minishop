<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repo\CartRepository;
use App\Repo\ProductsRepo;
use App\Services\CartService;
use Exception;

class CartController extends Controller
{
    private CartService $cartService;

    public function __construct()
    {
        $this->cartService = new CartService(
            new CartRepository(),
            new ProductsRepo()
        );
    }
    public function add(int $productId):void
    {
      try
      {
         $this->cartService->addProduct($productId);
         $_SESSION['success'] = 'محصول با موفقیت اضافه شد';
      }
      catch(Exception $e)
      {
         $_SESSION['error'] = $e->getMessage();
      }
      $this->redirect('cart');
    }
    public function index():void
    {
        $items = $this->cartService->getItems();
        $subtotal = $this->cartService->getSubtotal($items);
        $this->view('cart/index',compact('items','subtotal'));
    }
    public function update(int $itemId):void
    {
        try{
            $quantity = (int) ($_POST['quantity'] ?? 0);
            $this->cartService->updateQuantity($itemId,$quantity);
            $_SESSION['success'] = "سبد خرید با موفقیت بروز شد";
        }catch(Exception $e){
            $_SESSION['error'] = $e->getMessage();
        }
        $this->redirect('cart');
    }
    public function remove(int $itemId):void
    {
        try{
            $this->cartService->removeItem($itemId);
            $_SESSION['success'] = 'محصول با موفقیت حذف شد';            
        }catch(Exception $e){
            $_SESSION['error'] = $e->getMessage();
        }
        $this->redirect('cart');
    }
    public function checkout(): void
    {
        $errors = $this->cartService->checkout();

        if (!empty($errors)) {
            $_SESSION['cart_errors'] = $errors;
            $this->redirect('cart');
            return;
        }
        
        $_SESSION['success'] = 'سبد خرید شما معتبر است، آماده‌ی ادامه‌ی فرآیند خرید هستید';
        $this->redirect('cart');
    }
}