<?php
require_once "app/headers.php";
require_once "app/PersonController.php";

$requestUri = $_SERVER['REQUEST_URI'];

// validate route
validateRoute();

$uriParts = explode('api/', trim($requestUri, '/'));
$id = urldecode($uriParts[1]) ?? null;
$personController = new PersonController();

switch ($_SERVER["REQUEST_METHOD"]) {

    case 'POST':
        if($id) {
            header("HTTP/1.1 404 Route Not Found");
            echo json_encode(["error" => "Route not found"]);
            exit;
        }
        $request = json_decode(file_get_contents("php://input"));
        echo $personController->store($request);
        break;
    case 'GET':
        if ($id) {
            echo $personController->show($id);
        } else {
            echo $personController->index();
        }
        break;
    case 'PUT':
        $request = json_decode(file_get_contents("php://input"));
        if(!is_numeric($id)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(["error" => "Invalid 'id' parameter"]);
            exit;
        }
        if ($id) {
            echo $personController->update($request, $id); // Implement the 'update' method
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(["error" => "Missing 'id' parameter"]);
        }
        break;
    case 'DELETE':
        if(!is_numeric($id)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(["error" => "Invalid 'id' parameter"]);
            exit;
        }

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
