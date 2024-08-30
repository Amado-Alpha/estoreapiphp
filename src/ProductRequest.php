<?php

class ProductRequest
{
    public static function validate(array $data, ProductGateway $gateway, bool $is_new = true ): array
    {
        $errors = [];
        
        // Validate 'name'
        if ($is_new && empty($data["name"])) {
            $errors[] = "Name is required";
        } elseif (isset($data["name"]) && !is_string($data["name"])) {
            $errors[] = "Name must be a string";
        }
        
        // Validate 'description'
        if ($is_new && empty($data["description"])) {
            $errors[] = "Description is required";
        } elseif (isset($data["description"]) && !is_string($data["description"])) {
            $errors[] = "Description must be a string";
        }

         // Validate 'price'
        if ($is_new && empty($data["price"])) {
            $errors[] = "Price is required";
        } elseif (isset($data["price"]) && !filter_var($data["price"], FILTER_VALIDATE_FLOAT)) {
            $errors[] = "Price must be a valid decimal number";
        }
           
        // Validate 'category_id'
        if ($is_new && empty($data["category_id"])) {
            $errors[] = "Category ID is required";
        } elseif (isset($data["category_id"])) {
            if (!filter_var($data["category_id"], FILTER_VALIDATE_INT)) {
                $errors[] = "Category ID must be an integer";
            } else {
                // Check if the category ID exists in the database
                if (!$gateway->categoryExists((int)$data["category_id"])) {
                    $errors[] = "Category ID does not exist";
                }
            }
        }

        // Validate 'image_url'
        if ($is_new && empty($data["image_url"])) {
            $errors[] = "Image URL is required";
        } elseif (isset($data["image_url"]) && !filter_var($data["image_url"], FILTER_VALIDATE_URL)) {
            $errors[] = "Image URL must be a valid URL";
        }

        return $errors;
    }
}
