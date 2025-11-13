<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel</title>
</head>
<body>
    <h1>Bienvenido <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h1>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
