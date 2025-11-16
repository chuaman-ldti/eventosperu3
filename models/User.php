<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table = "users";

    public $id;
    public $username;
    public $password;
    public $role; // Propiedad añadida

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findByUsername($username) {
        // Esta consulta ya trae todos los campos, incluido 'role'
        $sql = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Función actualizada para incluir el rol.
     * Por defecto se asigna 'user' si no se especifica.
     */
    public function createUser($username, $password, $role = 'user') {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Consulta SQL actualizada para insertar el rol
            $sql = "INSERT INTO " . $this->table . " (username, password, role) VALUES (:username, :password, :role)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role); // Añadir binding para el rol
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>