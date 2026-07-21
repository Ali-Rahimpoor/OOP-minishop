<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Repo\ProductsRepo;

class ProductController extends Controller{
   public function index():void
   {      
      $this->view('products/add-edit');
   }
}