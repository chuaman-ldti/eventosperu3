<?php
require_once __DIR__ . '/../models/User.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        $userModel = new User();
        if ($userModel->createUser($username, $password)) {
            $message = "✅ Usuario creado correctamente.";
        } else {
            $message = "❌ Error al crear el usuario.";
        }
    } else {
        $message = "⚠️ Debes completar todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        input, button {
            display: block;
            width: 100%;
            padding: 0.6rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .msg {
            text-align: center;
            margin-top: 1rem;
            color: green;
        }
    </style>
</head>
<body>
    <form id="signupForm" method="post" action="">
        <h2>Registrar nuevo usuario</h2>
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Registrar</button>
        <?php if ($message): ?>
        <div id="signupMsg" class="msg"><?= htmlspecialchars($message) ?></div>
            <div class="msg"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
    </form>
    <script src="../assets/app.js"></script>
</body>
</html>
