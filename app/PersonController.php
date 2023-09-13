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
        } catch (PDOException) {
            http_response_code(500);
            return json_encode(["error" => "Error occurred."]);
        } catch (\Exception) {
            http_response_code(500);
            return json_encode(["error" => "Oops, something went wrong"]);
        }
    }

    public function show($id)
    {
        try {
            $person = self::getPerson($id);

            http_response_code(200);
            return json_encode($person);
        } catch (PDOException) {
            http_response_code(500);
            return json_encode(["error" => "Error occurred."]);
        } catch (\Exception) {
            http_response_code(500);
            return json_encode(["error" => "Oops, something went wrong"]);
        }
    }

    public function store($request)
    {
        $name = $request->name;
        self::validateName($name);

        try {
            $saved = Person::create([
                "name" => $name,
            ]);
            http_response_code(200);
            return json_encode(['message' => "Person saved successfully", "data" => $saved]);
        } catch (\PDOException) {
            http_response_code(500);
            return json_encode(["error" => "Error occurred."]);
        } catch (\Exception) {
            http_response_code(500);
            return json_encode(["error" => "Oops, something went wrong"]);
        }
    }

    public function update($request, $id)
    {
        $name = $request->name;

        try {
            $person = self::getPerson($id);
            self::validateName($name);

            Person::update([
                "name" => $name,
            ], $id);

            $person['name'] = $name;

            http_response_code(200);
            return json_encode(['message' => "Updated successfully", "data" => $person]);
        } catch (\PDOException) {
            http_response_code(500);
            return json_encode(["error" => "Error occurred."]);
        } catch (\Exception) {
            http_response_code(500);
            return json_encode(["error" => "Oops, something went wrong"]);
        }
    }

    public function destroy($id)
    {
        try {
            $person = self::getPerson($id);

            Person::delete($id);
            http_response_code(200);
            return json_encode(['message' => "Deleted successfully", "data" => $person]);
        } catch (\PDOException) {
            http_response_code(500);
            return json_encode(["error" => "Error occurred."]);
        } catch (\Exception) {
            http_response_code(500);
            return json_encode(["error" => "Oops, something went wrong"]);
        }
    }

    private static function getPerson($id)
    {
        $person = Person::find($id);
        if(!$person) {
            http_response_code(404);
            echo json_encode(["error" => "Person not found"]);
            exit;
        }
        return $person;
    }

    public static function validateName($name): void
    {
        // Validate and sanitize name
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        if (empty($name)) {
            http_response_code(422);
            echo json_encode(["error" => "Name field is required"]);
            exit;
        }

        if(!preg_match('/^[A-Za-z -]+$/', $name)) {
            http_response_code(422);
            echo json_encode(["error" => "Please provide a valid name"]);
            exit;
        }
    }
}