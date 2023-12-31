<?php
require_once "DbConnection.php";

class Person
{
    protected static string $table = "person";

    public static function all()
    {
        $conn = (new DbConnection())();

        $sqlQuery = "SELECT id, name FROM " . self::$table;
        $stmt = $conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $conn = (new DbConnection())();
        $column = is_numeric($id) ? 'id' : 'name';
        $sqlQuery = "SELECT id, name FROM " . self::$table . " WHERE $column = :value";
        $stmt = $conn->prepare($sqlQuery);
        if (is_numeric($id)) {
            $stmt->bindParam(':value', $id, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':value', $id, PDO::PARAM_STR);
        }
        $stmt->execute();

        // Fetch a single record
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function create(array $request)
    {
        $conn = (new DbConnection())();

        $sqlQuery = "INSERT INTO " . self::$table . " (`name`, `created_at`, `updated_at`) VALUES (:name, :created_at, :updated_at)";
        $stmt = $conn->prepare($sqlQuery);
        $dateTime = new \DateTime('now', new \DateTimeZone('UTC'));
        $currentDate = $dateTime->format('Y-m-d H:i:s');

        // Bind parameters from $request to the placeholders
        $stmt->bindParam(':name', $request['name'], PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $currentDate, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $currentDate, PDO::PARAM_STR);

        try {
            $stmt->execute();
            return self::find($conn->lastInsertId()); // Return true on success
        } catch (\PDOException $e) {
            return false; // Return false on failure
        }
    }

    /**
     * @throws \Exception
     */
    public static function update(array $request, $id)
    {
        $conn = (new DbConnection())();

        $dateTime = new \DateTime('now', new \DateTimeZone('UTC'));
        $currentDate = $dateTime->format('Y-m-d H:i:s');
        $sqlQuery = "UPDATE " . self::$table . " SET `name` = :name, `updated_at` = :updated_at WHERE id = :id";
        $stmt = $conn->prepare($sqlQuery);

        // Bind parameters from $request to the placeholders
        $stmt->bindParam(':name', $request['name'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $currentDate, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id,  PDO::PARAM_INT);

        try {
            $stmt->execute();
            return true;
        } catch (\PDOException) {
            return false; // Return false on failure
        }
    }

    public static function delete($id)
    {
        $conn = (new DbConnection())();
        $sqlQuery = "DELETE FROM " . self::$table . " WHERE `id` = :id";
        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindValue(':id', $id);

        try {
            $stmt->execute();
            return true;
            // Return true on success
        } catch (\PDOException) {
            return false; // Return false on failure
        }
    }
}