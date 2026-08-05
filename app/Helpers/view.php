<?php

use App\Core\Auth;
use Hekmatinasser\Verta\Verta;
function discount_percent(int $price, int $sale_price): int {
    if ($price <= 0 || $sale_price < 0 || $sale_price > $price) {        
        return 0; // Invalid input, return 0
    }
    
    $percent = round((($price - $sale_price) / $price) * 100);
    
    return $percent;
}
function show_percent(int $price, int $sale_price):mixed
{
    return !empty( discount_percent($price,$sale_price) ) ?discount_percent($price,$sale_price) . "%" : 'بدون تخفیف' ;
}
function get_status_label(string $status) : string
{
    $valid_statues = 
    [
        'publish' => 'منتشر شده',
        'draft'  => 'پیش نویس',
        'presale'=> 'پیش فروش',
        'expire' => 'منقضی شده'
    ];
    return $valid_statues[$status];
}
function to_jalali($date_string){
    $jalali = Verta::instance($date_string)->format('Y/m/d');
    return $jalali;
}
function product_img_src($img_src) {
    // اگر ورودی خالی یا null بود
    if (empty($img_src)) {
        return site_url('public/uploads/no-img.webp');
    }
        
    $full_path = BASE_PATH . '/storage/uploads/' . $img_src;    
    
    // بررسی وجود فایل
    if (file_exists($full_path)) {
        return site_url('storage/uploads/' . $img_src);
    } else {
        return site_url('public/uploads/no-image.webp');
    }
}
function htmle(string $value):string
{
    return htmlspecialchars($value,ENT_QUOTES,'UTF-8');
}
function is_admin(){
    return Auth::isAdmin();
}
function is_login(){
    return Auth::check();
}
function toToman(int $rial):int
{
    return (int) round($rial/10);
}