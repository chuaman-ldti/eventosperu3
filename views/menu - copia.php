<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);
$menu_class = ($current_page == 'menu.php') ? ' active' : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Eventos Perú</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <header class="header">
    <div class="brand">
        <div class="logo">UTP</div>
        <div>
            <h1>Eventos Perú</h1>
            <div style="font-size:13px;opacity:0.9">Gestión de Eventos</div>
        </div>
    </div>
    
    <nav class="main-nav">
        <a class="<?php echo ($current_page == 'menu.php') ? ' active' : ''; ?>">Menu</a>
        <a href="clients.php">Clientes</a>
        <a href="providers.php">Proveedores</a>
        <a href="events.php">Programacion</a>
    </nav>
    
    <div class="user-info">
        
        <span class="welcome-text">Bienvenido **<?php echo htmlspecialchars($_SESSION['username']); ?>** 👨‍💻</span>
        
        <a href="logout.php" class="logout-link">Cerrar Sesión</a>
        
    </div>
</header>

  <main class="container">
    <section class="card">
      <h2 style="margin-top:0">Panel de administración</h2>
      <p>Usa las tarjetas para gestionar clientes, proveedores y eventos. Diseño inspirado en festivales y conferencias.</p>
      <div class="controls" style="margin-top:14px">
        <a class="btn" href="clients.php">👥 Clientes</a>
        <a class="btn" href="providers.php">🏢 Proveedores</a>
        <a class="btn alt" href="events.php">🎟️ Eventos</a>
        <a class="btn alt" href="signup.php">🙍‍♂️ Nuevo Usuario</a>
      </div>
    </section>

    <section class="card">
      <h3>Accesos directos</h3>
      <div class="controls">
        <a class="btn" href="clients.php">Crear cliente</a>
        <a class="btn" href="providers.php">Crear proveedor</a>
        <a class="btn alt" href="events.php">Crear evento</a>
      </div>
    </section>

    <div class="footer">© Grupo 10 - JavaTeam</div>
  </main>
</body>
</html>