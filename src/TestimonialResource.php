<?php
class TestimonialResource
{
    public static function toArray(array $testimonial): array
    {
        return [
            'id' => $testimonial['id'],
            'author_firstname' => $testimonial['author_firstname'],
            'author_surname' => $testimonial['author_surname'],
            'company' => $testimonial['company'],
            'position' => $testimonial['position'],
            'content' => $testimonial['content'],
            'rating' => (int) $testimonial['rating'], // Cast rating to an integer
            'image_url' => $testimonial['image_url'],
            'created_at' => $testimonial['created_at'],
            'updated_at' => $testimonial['updated_at'],
        ];
    }

    public static function collection(array $testimonials): array
    {
        return array_map([self::class, 'toArray'], $testimonials);
    }
}
