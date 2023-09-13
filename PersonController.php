<?php
require_once "Person.php";
class PersonController
{
    public function store($request): void
    {
        $name = $request->name;
        if(empty($name)) {
            http_response_code(422);
            echo json_encode(["error" => "Name field is required"]);
            exit;
        }
        $saved = Person::create([
            "name" => $name,
        ]);
        if($saved){
            http_response_code(200);
            echo json_encode(['message' => "Person saved successfully", "data" => $saved]);
        }else{
            echo json_encode(["error" => "Oops something went wrong"]);
        }
    }

    public function index(): void {
        try {
            $response = Person::all();
            http_response_code(200);
            echo json_encode($response);
        }
        catch (Exception) {
            http_response_code(500);
            echo json_encode(["error" => "Oops something went wrong"]);
        }
    }
}