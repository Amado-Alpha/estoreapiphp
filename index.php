<?php

declare(strict_types=1);

// Auto-loading classes, For larger projects you can use compose auto-loader.
spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

// routing is kept simple for the purposes of the video, but we need to use a third part
//  y for a better solution.
// if ($parts[1] != "products") {
//     http_response_code(404);
//     exit;
// }

$id = $parts[2] ?? null;

$database = new Database("localhost", "product_db", "root", "");

// PRODUCTS
// $gateway = new ProductGateway($database);
// $controller = new ProductController($gateway);

// CATEGORIES
// $gateway = new CategoryGateway($database);

// $controller = new CategoryController($gateway);

// TESTIMONIALS
$gateway = new TestimonialGateway($database);
$controller = new TestimonialController($gateway);

// PROJECTS
$gateway = new ProjectGateway($database);
$controller = new ProjectController($gateway);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);













