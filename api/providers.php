<?php
// proveedores.php - PDO version
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/api_bootstrap.php";
$db = new Database();
$conn = $db->connect();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $conn->prepare("SELECT * FROM proveedores WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$res) resp_err("proveedores no encontrado", 404);
            resp_ok($res);
        } else {
            $stmt = $conn->query("SELECT * FROM proveedores ORDER BY id DESC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            resp_ok($rows);
        }
    }

    if ($method === 'POST') {
        $data = read_json();
        // Minimal validation: require 'nombre' or 'titulo' depending - try nombre
        if (empty($data['nombre']) && empty($data['titulo'])) resp_err("Campo obligatorio: nombre/titulo", 400);
        $fields = [];
        $params = [];
        // We will assume common fields: nombre, telefono, email, cliente_id, proveedor_id, fecha, estado, descripcion
        $possible = ['nombre','telefono','email','cliente_id','proveedor_id','fecha','estado','descripcion','titulo','precio'];
        foreach ($possible as $f) {
            if (isset($data[$f])) {
                $fields[] = $f;
                $params[$f] = $data[$f];
            }
        }
        if (empty($fields)) resp_err("No hay campos para insertar", 400);
        $cols = implode(", ", $fields);
        $place = ":" . implode(", :", $fields);
        $sql = "INSERT INTO proveedores ($cols) VALUES ($place)";
        $stmt = $conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        if ($stmt->execute()) {
            resp_ok(["success" => true, "id" => $conn->lastInsertId()], 201);
        } else {
            resp_err("Error al crear proveedores", 500);
        }
    }

    if ($method === 'PUT') {
        if (!isset($_GET['id'])) resp_err("Se requiere id", 400);
        $id = intval($_GET['id']);
        $data = read_json();
        $stmt = $conn->prepare("SELECT * FROM proveedores WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$existing) resp_err("proveedores no encontrado", 404);
        $updates = [];
        foreach ($data as $k => $v) {
            $updates[] = "$k = :$k";
        }
        if (empty($updates)) resp_err("No hay campos para actualizar", 400);
        $sql = "UPDATE proveedores SET " . implode(", ", $updates) . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        foreach ($data as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            resp_ok(["success" => true, "id" => $id]);
        } else {
            resp_err("Error al actualizar proveedores", 500);
        }
    }

    if ($method === 'DELETE') {
        if (!isset($_GET['id'])) resp_err("Se requiere id", 400);
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("DELETE FROM proveedores WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            resp_ok(["success" => true, "id" => $id]);
        } else {
            resp_err("Error al eliminar proveedores", 500);
        }
    }

    resp_err("Método no soportado", 405);

} catch (PDOException $e) {
    resp_err("Error de base de datos: " . $e->getMessage(), 500);
}
?>