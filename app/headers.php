<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

require __DIR__ .'/../vendor/autoload.php';

// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

date_default_timezone_set('UTC');

function validateRoute () {
    $requestUri = $_SERVER['REQUEST_URI'];

    // validate route
    if($_ENV['ENV'] == "prod"){
        $routePattern =  '#^/api#';
        $pattern = '#^/api(/[^/]+)?$#'; // validate route must not include /api/:id/others
    }else{
        $routePattern = '#^/[^/]+/api#';
        $pattern = '#^/[^/]+/api(/[^/]+)?$#';
    }

    if (!preg_match($routePattern, $requestUri) or !preg_match($pattern, $requestUri)) {
        header("HTTP/1.1 404 Route Not Found");
        echo json_encode(["error" => "Route not found"]);
        exit;
    }
}