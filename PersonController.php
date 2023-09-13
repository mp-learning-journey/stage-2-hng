<?php
require_once "Person.php";
class PersonController
{
    public function index()
    {
        try {
            $response = Person::all();
            http_response_code(200);
            return json_encode($response);
        }catch (PDOException) {
            http_response_code(500);
            return json_encode(["error" => "Error occurred."]);
        }
        catch (Exception) {
            http_response_code(500);
            return json_encode(["error" => "Oops, something went wrong"]);
        }
    }

    public function show($id)
    {
        try {
            $response = Person::find($id);
            if(!$response) {
                return self::personNotFound();
            }

            http_response_code(200);
            return json_encode($response);
        }
        catch (PDOException) {
            http_response_code(500);
            return json_encode(["error" => "Error occurred."]);
        }
        catch (Exception) {
            http_response_code(500);
            return json_encode(["error" => "Oops, something went wrong"]);
        }
    }

    public function store($request)
    {
        $name = $request->name;
        if(empty($name)) {
            http_response_code(422);
            return json_encode(["error" => "Name field is required"]);
        }
        try {
            $saved = Person::create([
                "name" => $name,
            ]);
            http_response_code(200);
            return json_encode(['message' => "Person saved successfully", "data" => $saved]);
        }
        catch (PDOException) {
            http_response_code(500);
            return json_encode(["error" => "Error occurred."]);
        }
        catch (Exception) {
            http_response_code(500);
            return json_encode(["error" => "Oops, something went wrong"]);
        }
    }

    public function update($request, $id)
    {
        $name = $request->name;
        if(empty($name)) {
            http_response_code(422);
            return json_encode(["error" => "Name field is required"]);
        }
        try {
            $updated = Person::update([
                "name" => $name,
            ], $id);

            if (!$updated) {
                return self::personNotFound();
            }

            http_response_code(200);
            return json_encode(['message' => "Updated successfully", "data" => $updated]);
        }
        catch (PDOException) {
            http_response_code(500);
            return json_encode(["error" => "Error occurred."]);
        }
        catch (Exception) {
            http_response_code(500);
            return json_encode(["error" => "Oops, something went wrong"]);
        }
    }

    public function destroy($id) {
        try {
            $person = Person::find($id);
            if(!$person) {
                return self::personNotFound();
            }

            Person::delete($id);
            http_response_code(200);
            return json_encode(['message' => "Deleted successfully", "data" => $person]);
        }
        catch (PDOException) {
            http_response_code(500);
            return json_encode(["error" => "Error occurred."]);
        }
        catch (Exception) {
            http_response_code(500);
            return json_encode(["error" => "Oops, something went wrong"]);
        }
    }

    private static function personNotFound()
    {
        http_response_code(404);
        return json_encode(["error" => "Person not found"]);
    }
}