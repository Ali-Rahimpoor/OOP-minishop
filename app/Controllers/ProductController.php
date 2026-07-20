<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Repo\ProductsRepo;

class ProductController extends Controller{
   public function index()
   {
      $repo = new ProductsRepo();
      $products = $repo->latest(20);
      $this->view('products/index',['products'=>$products]);
   }
}