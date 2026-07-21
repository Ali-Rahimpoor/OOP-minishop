<?php
function discount_percent(int $price, int $sale_price): int {
    if ($price <= 0 || $sale_price < 0 || $sale_price > $price) {        
        return 0; // Invalid input, return 0
    }
    
    $percent = round((($price - $sale_price) / $price) * 100);
    
    return $percent;
}