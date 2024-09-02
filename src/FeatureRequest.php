<?php

class FeatureRequest
{
    public static function validate(array $data, FeatureGateway $gateway, bool $is_new = true): array
    {
        $errors = [];
        
        // Validate 'description'
        if ($is_new && empty($data["description"])) {
            $errors[] = "Description is required";
        } elseif (isset($data["description"]) && !is_string($data["description"])) {
            $errors[] = "Description must be a string";
        } elseif ($is_new && $gateway->featureExists($data["description"])) {
            $errors[] = "Feature already exists";
        }

        return $errors;
    }
}