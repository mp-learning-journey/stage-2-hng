<?php
require_once "DbConnection.php";
class Person
{
    protected static string $table = "person";

    public static function all(){
        $conn = (new DbConnection())();

        $sqlQuery = "SELECT id, name FROM ". self::$table;
        $stmt = $conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $conn = (new DbConnection())();

        $sqlQuery = "SELECT id, name FROM " . self::$table . " WHERE id = :id";
        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch a single record
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create(Array $request){
        $conn = (new DbConnection())();

        $sqlQuery = "INSERT INTO " .self::$table. " (`name`) VALUES (:name)";
        $stmt = $conn->prepare($sqlQuery);

        // Bind parameters from $request to the placeholders
        $stmt->bindValue(':name', $request['name']);

        try {
            $stmt->execute();
            return self::find($conn->lastInsertId()); // Return true on success
        } catch (PDOException $e) {
            return false; // Return false on failure
        }
    }

    /**
     * @throws Exception
     */
    public static function update(Array $request, $id)
    {
        $conn = (new DbConnection())();

        $dateTime = new DateTime('now', new DateTimeZone('UTC'));
        $currentDate = $dateTime->format('Y-m-d H:i:s');
        $sqlQuery = "UPDATE " .self::$table. " SET `name` = :name, `updated_at` = :updated_at WHERE id = :id";
        $stmt = $conn->prepare($sqlQuery);

        // Bind parameters from $request to the placeholders
        $stmt->bindValue(':name', $request['name']);
        $stmt->bindValue(':updated_at', $currentDate);
        $stmt->bindValue(':id', $id);

        try {
            $stmt->execute();
            return self::find($id);
        } catch (PDOException) {
            return false; // Return false on failure
        }
    }

    public static function delete($id)
    {
        $conn = (new DbConnection())();
        $sqlQuery = "DELETE FROM " .self::$table. " WHERE `id` = :id";
        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindValue(':id', $id);

        try {
            $stmt->execute();
            return true;
            // Return true on success
        } catch (PDOException) {
            return false; // Return false on failure
        }
    }
}