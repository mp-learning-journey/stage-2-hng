<?php
require_once "headers.php";
require_once "PersonController.php";

$personController = new PersonController();
switch ($_SERVER["REQUEST_METHOD"]) {

    case 'POST':
        $request = json_decode(file_get_contents("php://input"));
        echo $personController->store($request);
        break;
    case 'GET':
        $id = $_GET['id'] ?? null;
        if ($id) {
            echo $personController->show($id); // Implement the 'show' method
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
