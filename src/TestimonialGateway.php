<?php

class TestimonialGateway
{
    private PDO $conn;
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array
    {
        $sql = "SELECT *
                FROM testimonials";
                
        $stmt = $this->conn->query($sql);
        
        $data = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $data[] = $row;
        }
        
        return $data;
    }
    
    public function create(array $data): string
    {
        $sql = "INSERT INTO testimonials (author_firstname, author_surname, company, position, content, rating, image_url)
                VALUES (:author_firstname, :author_surname, :company, :position, :content, :rating, :image_url)";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":author_firstname", $data["author_firstname"], PDO::PARAM_STR);
        $stmt->bindValue(":author_surname", $data["author_surname"], PDO::PARAM_STR);
        $stmt->bindValue(":company", $data["company"] ?? null, PDO::PARAM_STR); // Nullable
        $stmt->bindValue(":position", $data["position"] ?? null, PDO::PARAM_STR); // Nullable
        $stmt->bindValue(":content", $data["content"], PDO::PARAM_STR);
        $stmt->bindValue(":rating", $data["rating"] ?? 5, PDO::PARAM_INT); // Default to 5 if not provided
        $stmt->bindValue(":image_url", $data["image_url"] ?? null, PDO::PARAM_STR); // Nullable
        
        $stmt->execute();
        
        return $this->conn->lastInsertId();
    }

    
    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM testimonials
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function update(array $current, array $new): int
    {
        $sql = "UPDATE testimonials
                SET author_firstname = :author_firstname, 
                    author_surname = :author_surname, 
                    company = :company, 
                    position = :position, 
                    content = :content, 
                    rating = :rating, 
                    image_url = :image_url
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":author_firstname", $new["author_firstname"] ?? $current["author_firstname"], PDO::PARAM_STR);
        $stmt->bindValue(":author_surname", $new["author_surname"] ?? $current["author_surname"], PDO::PARAM_STR);
        $stmt->bindValue(":company", $new["company"] ?? $current["company"], PDO::PARAM_STR);
        $stmt->bindValue(":position", $new["position"] ?? $current["position"], PDO::PARAM_STR);
        $stmt->bindValue(":content", $new["content"] ?? $current["content"], PDO::PARAM_STR);
        $stmt->bindValue(":rating", $new["rating"] ?? $current["rating"], PDO::PARAM_INT);
        $stmt->bindValue(":image_url", $new["image_url"] ?? $current["image_url"], PDO::PARAM_STR);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }


    
    public function delete(string $id): int
    {
        $sql = "DELETE FROM testimonials
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }
}











