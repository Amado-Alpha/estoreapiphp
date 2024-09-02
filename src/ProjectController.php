<?php

class ProjectController
{
    public function __construct(private ProjectGateway $gateway)
    {
    }
    
    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }
    
    private function processResourceRequest(string $method, string $id): void
    {
        $project = $this->gateway->get($id);
        
        if (!$project) {
            http_response_code(404);
            echo json_encode(["message" => "Project not found"]);
            return;
        }
        
        switch ($method) {
            case "GET":
                echo json_encode(ProjectResource::toArray($project));
                break;
                
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = ProjectRequest::validate($data, $this->gateway, false);
                
                if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $rows = $this->gateway->update($project, $data);
                
                // Update project features
                if (isset($data['features']) && is_array($data['features'])) {
                    $this->gateway->syncFeatures($id, $data['features']);
                }
                
                echo json_encode([
                    "message" => "Project $id updated",
                    "rows" => $rows
                ]);
                break;
                
            case "DELETE":
                $rows = $this->gateway->delete($id);
                echo json_encode([
                    "message" => "Project $id deleted",
                    "rows" => $rows
                ]);
                break;
                
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }
    }
    
    private function processCollectionRequest(string $method): void
    {
        switch ($method) {
            case "GET":
                $projects = $this->gateway->getAll();
                echo json_encode(ProjectResource::collection($projects));
                break;
                
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = ProjectRequest::validate($data, $this->gateway, true);
                
                if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $id = $this->gateway->create($data);
                
                // Attach features to the new project
                if (isset($data['features']) && is_array($data['features'])) {
                    $this->gateway->attachFeatures($id, $data['features']);
                }
                
                http_response_code(201);
                echo json_encode([
                    "message" => "Project created",
                    "id" => $id
                ]);
                break;
            
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }
}
