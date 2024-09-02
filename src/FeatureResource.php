<?php

class FeatureResource
{
    public static function toArray(array $feature): array
    {
        return [
            'id' => $feature['id'],
            'description' => $feature['description'],
            'created_at' => $feature['created_at'],
            'updated_at' => $feature['updated_at'],
        ];
    }

    public static function collection(array $features): array
    {
        return array_map([self::class, 'toArray'], $features);
    }
}
