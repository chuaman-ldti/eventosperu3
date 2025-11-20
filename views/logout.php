<?php
session_start();


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Cerrar sesión
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cerrar Sesión - EVENTOS PERU</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>

    <div class="container">
        <div class="top">
            <h1>EVENTOS PERU</h1>
        </div>

        <div class="card">
            <h2>Sesión cerrada</h2>
            <p>Has cerrado sesión correctamente.</p>
            <a href="login.php" class="btn">Volver al inicio de sesión</a>
        </div>
    </div>

</body>
</html>

