<?php
// proveedores.php - PDO version
require_once __DIR__ . "/../config/database.php";
header('Content-Type: application/json; charset=utf-8');

// Debug temporal: registrar petición entrante
file_put_contents(__DIR__ . '/providers_debug.log',
  date('c') . " REMOTE=" . ($_SERVER['REMOTE_ADDR'] ?? 'cli') .
  " METHOD=" . $_SERVER['REQUEST_METHOD'] .
  " RAW=" . file_get_contents('php://input') .
  " POST=" . print_r($_POST, true) . PHP_EOL,
  FILE_APPEND
);

function resp_ok($data = null, $code = 200) {
    http_response_code($code);
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}
function resp_err($msg = 'Error', $code = 400) {
    http_response_code($code);
    echo json_encode(['error' => true, 'message' => $msg]);
    exit;
}
function read_json() {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}
function get_input() {
    $d = read_json();
    if (!$d && $_POST) $d = $_POST;
    return $d ?: [];
}

$db = new Database();
$conn = $db->connect();
$method = $_SERVER['REQUEST_METHOD'];

$allowed = ['nombre','categoria','distrito','precio','reputacion','experiencia'];

try {
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $conn->prepare("SELECT id,nombre,categoria,distrito,precio,reputacion,experiencia,created_at FROM proveedores WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) resp_err('Proveedor no encontrado',404);
            resp_ok($row);
        } else {
            $stmt = $conn->query("SELECT id,nombre,categoria,distrito,precio,reputacion,experiencia,created_at FROM proveedores ORDER BY id DESC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            resp_ok($rows);
        }
    }

    if ($method === 'POST') {
        $data = get_input();
        $fields = []; $params = [];
        foreach ($allowed as $f) {
            if (isset($data[$f])) { $fields[] = $f; $params[$f] = $data[$f]; }
        }
        if (empty($fields)) resp_err('No hay campos para insertar',400);
        $cols = implode(', ', $fields);
        $place = ':' . implode(', :', $fields);
        $sql = "INSERT INTO proveedores ($cols) VALUES ($place)";
        $stmt = $conn->prepare($sql);
        foreach ($params as $k=>$v) $stmt->bindValue(':' . $k, $v);
        if ($stmt->execute()) resp_ok(['id' => $conn->lastInsertId()],201);
        resp_err('Error al insertar',500);
    }

    if ($method === 'PUT') {
        if (!isset($_GET['id'])) resp_err('Se requiere id',400);
        $id = intval($_GET['id']);
        $data = get_input();
        $updates = [];
        foreach ($allowed as $f) if (isset($data[$f])) $updates[$f] = $data[$f];
        if (empty($updates)) resp_err('No hay campos para actualizar',400);
        $set = implode(', ', array_map(function($k){return "$k = :$k";}, array_keys($updates)));
        $sql = "UPDATE proveedores SET $set WHERE id = :id";
        $stmt = $conn->prepare($sql);
        foreach ($updates as $k=>$v) $stmt->bindValue(':' . $k, $v);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) resp_ok(['id'=>$id]);
        resp_err('Error al actualizar',500);
    }

    if ($method === 'DELETE') {
        if (!isset($_GET['id'])) resp_err('Se requiere id',400);
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("DELETE FROM proveedores WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) resp_ok(['id'=>$id]);
        resp_err('Error al eliminar',500);
    }

    resp_err('Método no soportado',405);

} catch (PDOException $e) {
    resp_err('DB error: ' . $e->getMessage(),500);
}
?>
console.log('app.js cargado?', !!window.loadTable, 'typeof loadTable:', typeof loadTable);