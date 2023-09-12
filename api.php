<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers");
require_once "Person.php";
date_default_timezone_set('UTC');

switch ($_SERVER["REQUEST_METHOD"]) {
    case 'POST':
        Person::create([
            "name" => $_POST['name'],
        ]);
        break;
    case 'GET':
        $response = Person::all();
        http_response_code(200);
        echo json_encode($response);
        break;
    default:
        header("HTTP/1.1 404 Route Not Found");
        echo json_encode(["error" => "Route not found"]);
        break;
}
