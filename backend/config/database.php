<?php
require __DIR__ . '../vendor/autoload.php';

use Dotenv\Dotenv;


class Database {
    private $host;
    private $database;
    private $username;
    private $password;
    private $pdo;
    
    public function __construct()
    {
        //Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $this->host = $_ENV['DB_HOST'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
        $this->database = $_ENV['DB_NAME'];

        $this->getConnection();
    }


    private function getConnection() {

        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->database}",
                $this->username, 
                $this->password 
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        return $this->pdo;
    }
}

?>