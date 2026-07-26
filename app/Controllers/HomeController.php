<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repo\ProductsRepo;

class HomeController extends Controller
{
    public function index(): void
    {
        $filters = [
        'search'     => trim($_GET['search'] ?? ''),
        'status'     => $_GET['status'] ?? '',
        'price_from' => $_GET['price_from'] ?? '',
        'price_to'   => $_GET['price_to'] ?? '',
        'sort'       => $_GET['sort'] ?? '',
        'dir'        => $_GET['dir'] ?? '',
      ];
      $perpage = 10;
      $page = max(1,(int) ($_GET['page'] ?? 1));
      
      $repo = new ProductsRepo();
      $products = $repo->filter($filters,false,$page,$perpage);
      $total = $repo->countFiltered($filters,false);
      $lastPage = (int)  ceil($total/$perpage);
      $this->view('home',[
        'products'  => $products,
        'filters'   => $filters,
        'page'      => $page,
        'lastPage'  => max(1, $lastPage),
        'total'     => $total,
      ]);
    }
    public function list():void
    {
      $filters = [
        'search'     => trim($_GET['search'] ?? ''),
        'status'     => $_GET['status'] ?? '',
        'price_from' => $_GET['price_from'] ?? '',
        'price_to'   => $_GET['price_to'] ?? '',
        'sort'       => $_GET['sort'] ?? '',
        'dir'        => $_GET['dir'] ?? '',
      ];
      $repo = new ProductsRepo();
      $products = $repo->filter($filters);
      $this->view('home',[
         'products' => $products,
         'filters'  => $filters
      ]);
    }
}