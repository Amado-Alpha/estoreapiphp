<?php

class ProductResource
{
    public static function toArray(array $product): array
    {
        return [
            'id' => $product['id'],
            'name' => $product['name'],
            'description' => $product['description'],
            'price' => (float) $product['price'],
            'category_id' => $product['category_id'],
            'image_url' => $product['image_url'],
            'created_at' => $product['created_at'],
            'updated_at' => $product['updated_at'],
        ];
    }

    public static function collection(array $products): array
    {
        return array_map([self::class, 'toArray'], $products);
    }
}
