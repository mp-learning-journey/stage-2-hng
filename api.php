<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers");
require_once "PersonController.php";
date_default_timezone_set('UTC');
$personController = new PersonController();
switch ($_SERVER["REQUEST_METHOD"]) {

    case 'POST':
        $request = json_decode(file_get_contents("php://input"));
        $personController->store($request);
        break;
    case 'GET':
        $personController->index();
        break;
    case 'PUT':
        break;
    case 'DELETE':
        break;
    default:
        header("HTTP/1.1 404 Route Not Found");
        echo json_encode(["error" => "Route not found"]);
        break;
}
