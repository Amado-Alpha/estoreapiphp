<?php

class ProjectRequest
{
    public static function validate(array $data, ProjectGateway $gateway, bool $is_new = true): array
    {
        $errors = [];
        
        // Validate 'title'
        if ($is_new && empty($data["title"])) {
            $errors[] = "Title is required";
        } elseif (isset($data["title"]) && !is_string($data["title"])) {
            $errors[] = "Title must be a string";
        }
        
        // Validate 'description'
        if ($is_new && empty($data["description"])) {
            $errors[] = "Description is required";
        } elseif (isset($data["description"]) && !is_string($data["description"])) {
            $errors[] = "Description must be a string";
        }
        
        // Validate 'image_url'
        if (isset($data["image_url"]) && !filter_var($data["image_url"], FILTER_VALIDATE_URL)) {
            $errors[] = "Image URL must be a valid URL";
        }

        // Validate 'features'
        if (isset($data["features"]) && !is_array($data["features"])) {
            $errors[] = "Features must be an array of IDs";
        } elseif (isset($data["features"]) && $is_new) {
            foreach ($data["features"] as $featureId) {
                if (!is_int($featureId) || $featureId <= 0) {
                    $errors[] = "Each feature ID must be a positive integer";
                }
            }
        }

        return $errors;
    }
}
