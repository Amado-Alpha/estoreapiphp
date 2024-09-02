<?php
class TestimonialRequest
{
    public static function validate(array $data, TestimonialGateway $gateway, bool $is_new = true): array
    {
        $errors = [];

        // Validate 'author_firstname'
        if ($is_new && empty($data["author_firstname"])) {
            $errors[] = "Author's first name is required";
        } elseif (isset($data["author_firstname"]) && !is_string($data["author_firstname"])) {
            $errors[] = "Author's first name must be a string";
        }

        // Validate 'author_surname'
        if ($is_new && empty($data["author_surname"])) {
            $errors[] = "Author's surname is required";
        } elseif (isset($data["author_surname"]) && !is_string($data["author_surname"])) {
            $errors[] = "Author's surname must be a string";
        }

        // Validate 'company'
        if (isset($data["company"]) && !is_string($data["company"])) {
            $errors[] = "Company must be a string";
        }

        // Validate 'position'
        if (isset($data["position"]) && !is_string($data["position"])) {
            $errors[] = "Position must be a string";
        }

        // Validate 'content'
        if ($is_new && empty($data["content"])) {
            $errors[] = "Content is required";
        } elseif (isset($data["content"]) && !is_string($data["content"])) {
            $errors[] = "Content must be a string";
        }

        // Validate 'rating'
        if (isset($data["rating"])) {
            if (!filter_var($data["rating"], FILTER_VALIDATE_INT)) {
                $errors[] = "Rating must be an integer";
            } elseif ($data["rating"] < 1 || $data["rating"] > 5) {
                $errors[] = "Rating must be between 1 and 5";
            }
        }

        // Validate 'image_url'
        if (isset($data["image_url"]) && !filter_var($data["image_url"], FILTER_VALIDATE_URL)) {
            $errors[] = "Image URL must be a valid URL";
        }

        return $errors;
    }
}
