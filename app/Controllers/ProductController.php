<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Repo\ProductsRepo;
use App\Services\FileUploadService;
use App\Validators\ProductValidator;
use App\Core\Auth;
class ProductController extends Controller{
   private ProductsRepo $productsRepo;
   public function __construct()
   {
      Auth::requireAdmin();
      $this->productsRepo = new ProductsRepo();
   }
   public function index(int|null $id=null):void
   {           
      
      if(!isset($id)){         
         $this->view('products/add-edit');
      }else{
      $product = $this->productsRepo->findById($id);
      if(!$product){
         http_response_code(404);
         die('NOT FOUND !');
      }
      $this->view('products/add-edit',['product'=>$product]);      
      }
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
   public function update(int $id):void
   {
      
      $product = $this->productsRepo->findById($id);
      
      if(!$product){
         die("ERROR IN EDIT");
      }
      
      $data = [
         'ID'          => $id,
        'title'       => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'price'       => (int) ($_POST['price'] ?? 0),
        'sale_price'  => (int) ($_POST['sale_price'] ?? 0),
        'stock'       => (int) ($_POST['stock'] ?? 0),
        'status'      => $_POST['status'] ?? 'draft',
        'thumbnail'   => $product->thumbnail,
      ];
      $erros = ProductValidator::validate($data);
      if(!empty($erros)){
         die(print_r($erros, true));
      }

      if (
         isset($_FILES['thumbnail']) &&
         $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK
      ) {
         $uploadService = new FileUploadService();

         $data['thumbnail'] = $uploadService->upload(
               $_FILES['thumbnail'],
               [
                  'image/jpg',
                  'image/png',
                  'image/webp',
                  'image/jpeg'
               ]
         );
      }
      $product = Product::fromArray($data);
      $this->productsRepo->update($id,$product);
      $this->redirect(site_url('products/').$id . "?action=edited");

   }
   public function delete(int $id):void
   {
      
      $product = $this->productsRepo->findById($id);
      if(!$product){
         die("NOT FOUND PRODUCT");
      }
      $this->productsRepo->delete($id);
      $this->redirect(site_url('?action=deleted'));
   }
}