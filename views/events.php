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
        <a href="clients.php">Clientes</a>
        <a href="providers.php">Proveedores</a>
        <a class="<?php echo ($current_page == 'events.php') ? ' active' : ''; ?>">Programacion</a>
    </nav>
    
    <div class="user-info">
        
        <span class="welcome-text">Bienvenido **<?php echo htmlspecialchars($_SESSION['username']); ?>** 👨‍💻</span>
        
        <a href="logout.php" class="logout-link">Cerrar Sesión</a>
    </div>
</header>

  <main class="container">
    <section class="card">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0">Programación de Eventos</h2>
        <a class="back-link" href="menu.php">🔙 Volver al Menú</a>
      </div>

      <form id="events-form" class="card" style="margin-top:12px">
    
    <input type="hidden" name="id">

    <div class="form-2-cols">
        
        <div class="form-field">
            <label for="nombre_evento">Nombre del Evento</label>
            <input type="text" id="nombre_evento" name="nombre" placeholder="Escriba el nombre del evento" required>
        </div>

        <div class="form-field">
            <label for="fecha_evento">Fecha</label>
            <input type="date" id="fecha_evento" name="fecha" required>
        </div>

        <div class="form-field">
            <label for="ubicacion_evento">Ubicación</label>
            <input type="text" id="ubicacion_evento" name="ubicacion" placeholder="Escriba la ubicación" required>
        </div>

        <div class="form-field">
            <label for="estado_evento">Estado</label>
            <select id="estado_evento" name="estado" required>
                <option value="activo">Activo</option>
                <option value="pendiente">Pendiente</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>

    </div>
    <div class="controls" style="margin-top:20px;">
        <button class="btn" type="submit">Guardar Evento</button>
    </div>
    
</form>
      

      <div class="card table-wrap" style="margin-top:10px">
        <table id="events-table">
          <thead>
            <tr>
              <th>ID</th><th>Nombre</th><th>Fecha</th><th>Ubicacion</th><th>Estado</th><th></th><th></th>
            </tr>
          </thead>
          <tbody><tr><td colspan="7">Cargando...</td></tr></tbody>
        </table>
      </div>
    </section>
  </main>
</body>
</html>