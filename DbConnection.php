<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');

// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class DbConnection {
    private $conn;

    public function __invoke(): ?PDO
    {
        $this->conn = null;

        try {
            $dbHost = $_ENV['DB_HOST'];
            $dbName = $_ENV['DB_NAME'];
            $dbUsername = $_ENV['DB_USERNAME'];
            $dbPassword = $_ENV['DB_PASSWORD'];

            // Check if required environment variables are set
            if (empty($dbHost) || empty($dbName) || empty($dbUsername) || empty($dbPassword)) {
                throw new Exception("Missing one or more required environment variables.");
            }

            $this->conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
            $this->conn->exec("set names utf8");
        } catch (PDOException $e) {
            // Handle PDO errors
            echo "PDO Error: " . $e->getMessage();
        } catch (Exception $e) {
            // Handle other errors (e.g., missing environment variables)
            echo "Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}
