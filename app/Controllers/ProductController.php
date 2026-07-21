<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Repo\ProductsRepo;
use App\Services\FileUploadService;
use App\Validators\ProductValidator;

class ProductController extends Controller{
   private ProductsRepo $productsRepo;
   public function __construct()
   {
      $this->productsRepo = new ProductsRepo();
   }
   public function index():void
   {      
      $this->view('products/add-edit');
   }
   public function store():void
   {
      $data = $_POST;
      $erros = ProductValidator::validate($data);
      if(!empty($erros)){
         die(print_r($erros,true));
      }
      $thumbnail = 'no-product.jpg';
      if(isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK){
         $uploadService = new FileUploadService();
         $thumbnail = $uploadService->upload($_FILES['thumbnail'],['image/jpg','image/png','image/webp','image/jpeg']);
      }
      $product = Product::fromArray([
         ...$data,
         'thumbnail' => $thumbnail
      ]);
        
      $this->productsRepo->create($product);
      $this->redirect('/');        
        
   }
}