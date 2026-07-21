<?php

namespace App\Validators;

class ProductValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (empty(trim($data['title'] ?? ''))) {
            $errors['title'] = 'نام محصول الزامی است.';
        }

        if (!isset($data['price']) || $data['price'] < 0) {
            $errors['price'] = 'قیمت محصول معتبر نیست.';
        }

        if (!isset($data['sale_price']) || $data['sale_price'] < 0) {
            $errors['sale_price'] = 'قیمت فروش معتبر نیست.';
        }

        if (!isset($data['stock']) || $data['stock'] < 0) {
            $errors['stock'] = 'موجودی محصول معتبر نیست.';
        }

        $allowedStatuses = [
            'publish',
            'draft',
            'presale'
        ];

        if (
            !isset($data['status']) ||
            !in_array($data['status'], $allowedStatuses)
        ) {
            $errors['status'] = 'وضعیت محصول معتبر نیست.';
        }

        return $errors;
    }
}