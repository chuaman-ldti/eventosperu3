<?php
// clients.php - PDO version
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/api_bootstrap.php";
$db = new Database();
$conn = $db->connect();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$res) resp_err("Cliente no encontrado", 404);
            resp_ok($res);
        } else {
            $stmt = $conn->query("SELECT * FROM clientes ORDER BY id DESC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            resp_ok($rows);
        }
    }

    if ($method === 'POST') {
        $data = read_json();
        if (empty($data['nombre'])) resp_err("Campo obligatorio: nombre", 400);
        $stmt = $conn->prepare("INSERT INTO clientes (nombre, telefono, email) VALUES (:nombre, :telefono, :email)");
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':email', $data['email']);
        if ($stmt->execute()) {
            resp_ok(["success" => true, "id" => $conn->lastInsertId()], 201);
        } else {
            resp_err("Error al crear cliente", 500);
        }
    }

    if ($method === 'PUT') {
        if (!isset($_GET['id'])) resp_err("Se requiere id", 400);
        $id = intval($_GET['id']);
        $data = read_json();
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$existing) resp_err("Cliente no encontrado", 404);
        $nombre = $data['nombre'] ?? $existing['nombre'];
        $telefono = $data['telefono'] ?? $existing['telefono'];
        $email = $data['email'] ?? $existing['email'];
        $stmt = $conn->prepare("UPDATE clientes SET nombre = :nombre, telefono = :telefono, email = :email WHERE id = :id");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            resp_ok(["success" => true, "id" => $id]);
        } else {
            resp_err("Error al actualizar cliente", 500);
        }
    }

    if ($method === 'DELETE') {
        if (!isset($_GET['id'])) resp_err("Se requiere id", 400);
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("DELETE FROM clientes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            resp_ok(["success" => true, "id" => $id]);
        } else {
            resp_err("Error al eliminar cliente", 500);
        }
    }

    resp_err("Método no soportado", 405);

} catch (PDOException $e) {
    resp_err("Error de base de datos: " . $e->getMessage(), 500);
}
?>