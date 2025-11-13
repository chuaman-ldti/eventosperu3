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
  <meta charset="UTF-8">
  <title>Eventos Perú</title>
  <link rel="stylesheet" href="../assets/style.css">
  <script src="../assets/app.js" defer></script>
</head>
<body>
  <head>
  <meta charset="UTF-8">
  <title>Eventos Perú</title>
  <link rel="stylesheet" href="../assets/style.css">
  <script src="../assets/app.js" defer></script>
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
        <a href="menu.php">Menu</a>
        <a class="<?php echo ($current_page == 'clients.php') ? ' active' : ''; ?>">Clientes</a>
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
      <div style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0">Clientes</h2>
        <a class="back-link" href="menu.php">🔙 Volver al Menú</a>
      </div>

      <form id="clients-form" class="card" style="margin-top:12px">
    
    <input type="hidden" name="id">

    <div class="form-2-cols">
        
        <div class="form-field">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Escriba el nombre" required>
        </div>

        <div class="form-field">
            <label for="telefono">Teléfono</label>
            <input type="tel" id="telefono" name="telefono" placeholder="Escriba el teléfono" required>
        </div>

        <div class="form-field">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" placeholder="Escriba el email" required>
        </div>

        <div class="form-field">
            </div>

    </div>
    <div class="controls" style="margin-top:20px;">
        <button class="btn" type="submit">Guardar</button>
    </div>
    
</form>

      <div class="card table-wrap" style="margin-top:10px">
        <table id="clients-table">
          <thead>
            <tr>
              <th>ID</th><th>Nombre</th><th>Telefono</th><th>Email</th><th></th><th></th>
            </tr>
          </thead>
          <tbody><tr><td colspan="6">Cargando...</td></tr></tbody>
        </table>
      </div>
    </section>
  </main>
</body>
</html>