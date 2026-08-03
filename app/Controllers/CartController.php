<?php

namespace App\Controllers;

use App\Core\Auth;
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
         $this->cartService->addProduct(Auth::user_id(),$productId);
         $_SESSION['success'] = 'محصول با موفقیت اضافه شد';
      }
      catch(Exception $e)
      {
         $_SESSION['error'] = $e->getMessage();
      }
      $this->redirect(site_url('cart'));
    }
    public function index():void
    {
        $this->cartService->index();
    }
}