<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;

class JwtMiddleware
{
    private string $secretKey = 'your_secret_key';

    public function __invoke(Request $request, $handler): Response
    {
        $response = new SlimResponse();

        $headers = $request->getHeaders();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'][0];
            $token = str_replace('Bearer ', '', $authHeader);
            try {
                JWT::decode($token, $this->secretKey, ['HS256']);
            } catch (\Exception $e) {
                return $response->withStatus(401)->withJson(['message' => 'Unauthorized']);
            }
        } else {
            return $response->withStatus(401)->withJson(['message' => 'Unauthorized']);
        }

        return $handler->handle($request);
    }
}
