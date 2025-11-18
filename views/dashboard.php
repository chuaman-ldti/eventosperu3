<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel - EVENTOS PERU</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <h1>Bienvenido <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h1>
    <a href="logout.php">Cerrar sesión</a>

    <div class="container">
    <div class="top">
      <h1>EVENTOS PERU</h1>
    </div>

    <div class="card">
      <h2>Panel</h2>
      <ul>
        <li><a href="events.php">Gestión de Eventos</a></li>
        <li><a href="providers.php">Gestión de Proveedores</a></li>
        <li><a href="clients.php">Gestión de Clientes</a></li>
      </ul>
    </div>

  </div>
</body>
</html>
