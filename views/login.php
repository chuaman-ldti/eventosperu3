<?php
require_once __DIR__ . '/../controllers/AuthController.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]?? '');
    $password = trim($_POST["password"]?? '');
    $error = AuthController::login($username, $password);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .login-box {
            width: 320px; margin: 100px auto; padding: 20px;
            background: #fff; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        input, button { width: 100%; padding: 10px; margin: 8px 0; }
        button { background: #007BFF; color: #fff; border: none; border-radius: 5px; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
<div class="login-box">
    <h2>Iniciar Sesión</h2>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <p id="loginError" class="error"></p>
    <form id="loginForm" method="POST">
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
</div>
    <script src="../assets/app.js"></script>
</body>
</html>
