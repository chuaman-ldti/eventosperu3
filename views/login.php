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
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../assets/login.css">
</head>

<body>

<div class="login">
    <h2>INICIAR SESIÓN</h2>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>

        <input id="boton" type="submit" value="INGRESAR">
    </form>
</div>

</body>
</html>

