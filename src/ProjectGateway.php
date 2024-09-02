<?php

class ProjectGateway
{
    private PDO $conn;
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array
    {
        // $sql = "SELECT *
        //         FROM projects";
                
        // $stmt = $this->conn->query($sql);
        
        // $data = [];
        
        // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //     $data[] = $row;
        // }
        
        // return $data;

        $sql = "SELECT p.*, GROUP_CONCAT(f.description) as features
            FROM projects p
            LEFT JOIN project_feature pf ON p.id = pf.project_id
            LEFT JOIN features f ON pf.feature_id = f.id
            GROUP BY p.id";

        $stmt = $this->conn->query($sql);
        $projects = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['features'] = $row['features'] ? explode(',', $row['features']) : [];
            $projects[] = $row;
        }

        return $projects;
    }
    
    public function create(array $data): string
    {
        $sql = "INSERT INTO projects (title, description, image_url)
                VALUES (:title, :description, :image_url)";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":title", $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(":description", $data["description"], PDO::PARAM_STR);
        $stmt->bindValue(":image_url", $data["image_url"] ?? null, PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $this->conn->lastInsertId();
    }
    
    public function get(string $id): array | false
    {
        // $sql = "SELECT *
        //         FROM projects
        //         WHERE id = :id";
                
        // $stmt = $this->conn->prepare($sql);
        
        // $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        // $stmt->execute();
        
        // $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // return $data;

        $sql = "SELECT p.*, GROUP_CONCAT(f.description) as features
            FROM projects p
            LEFT JOIN project_feature pf ON p.id = pf.project_id
            LEFT JOIN features f ON pf.feature_id = f.id
            WHERE p.id = :id
            GROUP BY p.id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($project) {
            $project['features'] = $project['features'] ? explode(',', $project['features']) : [];
        }

        return $project;
    }
    
    public function update(array $current, array $new): int
    {
        $sql = "UPDATE projects
                SET title = :title, description = :description, image_url = :image_url
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":title", $new["title"] ?? $current["title"], PDO::PARAM_STR);
        $stmt->bindValue(":description", $new["description"] ?? $current["description"], PDO::PARAM_STR);
        $stmt->bindValue(":image_url", $new["image_url"] ?? $current["image_url"], PDO::PARAM_STR);
        
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM projects
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }

    public function attachFeatures(string $projectId, array $featureIds): void
    {
        $sql = "INSERT INTO project_feature (project_id, feature_id) VALUES (:project_id, :feature_id)";

        $stmt = $this->conn->prepare($sql);

        foreach ($featureIds as $featureId) {
            $stmt->bindValue(':project_id', $projectId, PDO::PARAM_INT);
            $stmt->bindValue(':feature_id', $featureId, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    public function syncFeatures(string $projectId, array $featureIds): void
    {
        // First, detach any features not in the new list
        if (!empty($featureIds)) {
            $placeholders = implode(',', array_fill(0, count($featureIds), '?'));
            $sql = "DELETE FROM project_feature WHERE project_id = ? AND feature_id NOT IN ($placeholders)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(array_merge([$projectId], $featureIds));
        } else {
            // If no features are provided, delete all existing ones for the project
            $sql = "DELETE FROM project_feature WHERE project_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$projectId]);
        }
    
        // Then, attach any new features
        $existingFeatureIds = $this->getFeatureIdsByProject($projectId);
        $newFeatureIds = array_diff($featureIds, $existingFeatureIds);
        
        if (!empty($newFeatureIds)) {
            $this->attachFeatures($projectId, $newFeatureIds);
        }
    }
    

    private function getFeatureIdsByProject(string $projectId): array
    {
        $sql = "SELECT feature_id FROM project_feature WHERE project_id = :project_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':project_id', $projectId, PDO::PARAM_INT);
        $stmt->execute();
        
        $featureIds = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $featureIds[] = $row['feature_id'];
        }
        
        return $featureIds;
    }

    // public function detachFeature(string $projectId, string $featureId): void
    // {
    //     $sql = "DELETE FROM project_feature
    //             WHERE project_id = :project_id AND feature_id = :feature_id";
                
    //     $stmt = $this->conn->prepare($sql);
        
    //     $stmt->bindValue(":project_id", $projectId, PDO::PARAM_INT);
    //     $stmt->bindValue(":feature_id", $featureId, PDO::PARAM_INT);
        
    //     $stmt->execute();
    // }
}
