<?php

require_once "app/headers.php";
require_once "app/PersonController.php";

$requestUri = $_SERVER['REQUEST_URI'];
$uriParts = explode('api', trim($requestUri, '/'));
$id = $uriParts[1] ?? null;

// Extract the resource (e.g., "api") and person ID
$resource = isset($uriParts[0]) ? $uriParts[0] : null;
$personId = isset($uriParts[1]) ? intval($uriParts[1]) : null;

$personController = new PersonController();
switch ($_SERVER["REQUEST_METHOD"]) {

    case 'POST':
        $request = json_decode(file_get_contents("php://input"));
        echo $personController->store($request);
        break;
    case 'GET':
        $id = $_GET['id'] ?? null;
        $name = $_GET['name'] ?? null;
        if ($id || $name) {
            echo $personController->show($id);
        } else {
            echo $personController->index();
        }
        break;
    case 'PUT':
        $id = $_GET['id'] ?? null;
        $request = json_decode(file_get_contents("php://input"));
        if ($id) {
            echo $personController->update($request, $id); // Implement the 'update' method
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(["error" => "Missing 'id' parameter"]);
        }
        break;
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($id) {
            echo $personController->destroy($id);
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(["error" => "Missing 'id' parameter"]);
        }
        break;
    default:
        header("HTTP/1.1 404 Route Not Found");
        echo json_encode(["error" => "Route not found"]);
        break;
}
