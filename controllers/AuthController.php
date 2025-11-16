<?php
require_once __DIR__ . '/../models/User.php';
session_start();

class AuthController {
    public static function login($username, $password) {
        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // <-- CAMBIO: Guardar el rol en la sesión
            header("Location: ../views/menu.php");
            exit;
        } else {
            return "Usuario o contraseña incorrectos.";
        }
    }

    public static function register($username, $password) {
        // Esta función parece no usarse en tu flujo de signup (signup.php usa el modelo directamente)
        // pero la actualizamos por si acaso, aunque signup.php necesita un campo 'role'.
        $userModel = new User();
        if ($userModel->findByUsername($username)) {
            return "El usuario ya existe.";
        }
        // Asumimos 'user' por defecto si se llama desde aquí
        if ($userModel->createUser($username, $password, 'user')) { 
            return true;
        } else {
            return "Error al crear usuario.";
        }
    }
}

// API-like handling for fetch requests with JSON body
$raw = file_get_contents('php://input');
$json = json_decode($raw, true);
if ($json) {
    header('Content-Type: application/json; charset=UTF-8');
    $action = $json['action'] ?? 'login';
    if ($action === 'login') {
        $username = $json['username'] ?? '';
        $password = $json['password'] ?? '';
        $userModel = new User();
        $user = $userModel->findByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // <-- CAMBIO: Guardar el rol en la sesión (API)
            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'Credenciales inválidas']);
            exit;
        }
    } elseif ($action === 'register' || $action === 'signup') {
        $username = $json['username'] ?? '';
        $password = $json['password'] ?? '';
        $role = $json['role'] ?? 'user'; // <-- CAMBIO: Aceptar rol desde la API
        
        $userModel = new User();
        if ($userModel->findByUsername($username)) {
            echo json_encode(['success' => false, 'error' => 'Usuario ya existe']);
            exit;
        }
        if ($userModel->createUser($username, $password, $role)) { // <-- CAMBIO: Pasar rol
            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al crear usuario']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Acción inválida']);
        exit;
    }
}
?>