<?php
// api_bootstrap.php
// Cabeceras JSON/CORS y funciones auxiliares comunes a los endpoints

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *"); // Permitir desde cualquier origen (para dev local)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Responder OPTIONS (preflight) y terminar
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

/**
 * Leer cuerpo JSON y retornarlo como array asociativo.
 * Para POST/PUT leemos php://input y decodificamos JSON.
 */
function read_json() {
    $raw = file_get_contents("php://input");
    if (!$raw) return [];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/** Enviar respuesta OK (JSON) */
function resp_ok($data = [], $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/** Enviar mensaje de error (JSON) */
function resp_err($msg = "Error", $code = 400) {
    http_response_code($code);
    echo json_encode(["error" => true, "message" => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}
?>
