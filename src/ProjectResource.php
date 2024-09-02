<?php

class ProjectResource
{
    public static function toArray(array $project): array
    {
        return [
            'id' => $project['id'],
            'title' => $project['title'],
            'description' => $project['description'],
            'image_url' => $project['image_url'] ?? null,
            'features' => $project['features'] ?? [], // Assuming features are included as IDs or related data
            'created_at' => $project['created_at'],
            'updated_at' => $project['updated_at'],
        ];
    }

    public static function collection(array $projects): array
    {
        return array_map([self::class, 'toArray'], $projects);
    }
}
