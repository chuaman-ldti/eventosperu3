<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table = "users";

    public $id;
    public $username;
    public $password;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findByUsername($username) {
        $sql = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($username, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO " . $this->table . " (username, password) VALUES (:username, :password)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>