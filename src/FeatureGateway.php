<?php

class FeatureGateway
{
    private PDO $conn;
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array
    {
        $sql = "SELECT * FROM features";
        $stmt = $this->conn->query($sql);
        
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    public function create(array $data): string
    {
        $sql = "INSERT INTO features (description) VALUES (:description)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":description", $data["description"], PDO::PARAM_STR);
        $stmt->execute();
        
        return $this->conn->lastInsertId();
    }
    
    public function get(string $id): array | false
    {
        $sql = "SELECT * FROM features WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update(array $current, array $new): int
    {
        $sql = "UPDATE features SET description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":description", $new["description"] ?? $current["description"], PDO::PARAM_STR);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->rowCount();
    }

    public function featureExists(string $description): bool
    {
        $sql = "SELECT COUNT(*) FROM features WHERE description = :description";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":description", $description, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }
    
    public function delete(string $id): int
    {
        $sql = "DELETE FROM features WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->rowCount();
    }
}
