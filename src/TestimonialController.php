<?php
class TestimonialController
{
    public function __construct(private TestimonialGateway $gateway)
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
        $testimonial = $this->gateway->get($id);
        
        if ( ! $testimonial) {
            http_response_code(404);
            echo json_encode(["message" => "Testimonial not found"]);
            return;
        }
        
        switch ($method) {
            case "GET":
                echo json_encode(TestimonialResource::toArray($testimonial));
                break;
                
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = TestimonialRequest::validate($data, $this->gateway, false);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $rows = $this->gateway->update($testimonial, $data);
                
                echo json_encode([
                    "message" => "Testimonial $id updated",
                    "rows" => $rows
                ]);
                break;
                
            case "DELETE":
                $rows = $this->gateway->delete($id);
                
                echo json_encode([
                    "message" => "Testimonial $id deleted",
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
                $testimonials = $this->gateway->getAll();
                echo json_encode(TestimonialResource::collection($testimonials));
                break;
                
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = TestimonialRequest::validate($data, $this->gateway, true);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $id = $this->gateway->create($data);
                
                http_response_code(201);
                echo json_encode([
                    "message" => "Testimonial created",
                    "id" => $id
                ]);
                break;
            
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }
}
