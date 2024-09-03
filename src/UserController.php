<?php

class UserController
{
    public function __construct(private UserGateway $gateway)
    {
    }

    public function register(): void
    {
        $data = (array) json_decode(file_get_contents("php://input"), true);

        $errors = UserRequest::validateRegistration($data);
        
        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(["errors" => $errors]);
            return;
        }

        $existingUser = $this->gateway->getByUsername($data['username']);
        if ($existingUser) {
            http_response_code(422);
            echo json_encode(["errors" => ["username" => "Username already exists"]]);
            return;
        }

        $existingEmail = $this->gateway->getByEmail($data['email']);
        if ($existingEmail) {
            http_response_code(422);
            echo json_encode(["errors" => ["email" => "Email already exists"]]);
            return;
        }

        $id = $this->gateway->register($data);

        http_response_code(201);
        echo json_encode(["message" => "User registered successfully", "id" => $id]);
    }

    public function login(): void
    {
        $data = (array) json_decode(file_get_contents("php://input"), true);

        $errors = UserRequest::validateLogin($data);
        
        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(["errors" => $errors]);
            return;
        }

        $user = $this->gateway->getByUsername($data['username']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid username or password"]);
            return;
        }

        // You can generate a JWT token or session here.
        echo json_encode(["message" => "Login successful"]);
    }
}
