<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repo\ProductsRepo;

class HomeController extends Controller
{
    public function index(): void
    {
        $repo = new ProductsRepo();
        $products = $repo->latest(5);
        $this->view('home',['products'=>$products]);
    }
}