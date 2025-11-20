<?php
// Inicia la sesión para poder usar variables de $_SESSION
session_start();

// Verifica si el usuario NO ha iniciado sesión
// Si no existe 'user_id', se redirige al login para proteger la página
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Guarda el nombre del archivo PHP actual ('providers.php')
// Sirve para resaltar en el menú la opción activa
$current_page = basename($_SERVER['PHP_SELF']);
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
    <!-- Título de la pestaña del navegador -->
  <title>Eventos Perú - Proveedores</title>
  <?php
    $cssPath = __DIR__ . '/../assets/style.css';

     // Si el archivo existe, se toma la fecha de modificación
    // Esto permite evitar problemas de caché en el navegador
    $cssVer = file_exists($cssPath) ? filemtime($cssPath) : time();
  ?>
  <link rel="stylesheet" href="../assets/style.css?v=<?php echo $cssVer; ?>">
  <script src="../assets/app.js" defer></script>
</head>


<body>
  <header class="header">

     <!-- Sección de marca UTP + título del sistema -->
    <div class="brand">
        <div class="logo">UTP</div>
        <div>
            <h1>Eventos Perú</h1>
            <div style="font-size:13px;opacity:0.9">Gestión de Eventos</div>
        </div>
    </div>

      <!-- Barra de navegación principal -->
    <nav class="main-nav">
        <a href="menu.php" class="<?php echo ($current_page == 'menu.php') ? 'active' : ''; ?>">Menu</a>
        <a href="clients.php" class="<?php echo ($current_page == 'clients.php') ? 'active' : ''; ?>">Clientes</a>
        <a href="providers.php" class="<?php echo ($current_page == 'providers.php') ? 'active' : ''; ?>">Proveedores</a>
        <a href="events.php" class="<?php echo ($current_page == 'events.php') ? 'active' : ''; ?>">Programacion</a>
    </nav>
    
        <!-- Información del usuario y botón de cerrar sesión -->
    <div class="user-info">
        <span class="welcome-text">Bienvenido <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?> 👨‍💻</span>
        <a href="logout.php" class="logout-link">Cerrar Sesión</a>
    </div>
  </header>


  <main class="container">
    <section class="card">

    <!-- Encabezado de la sección Proveedores -->
      <div style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0">Proveedores</h2>

         <!-- Enlace para regresar al menú principal -->
        <a class="back-link" href="menu.php">🔙 Volver al Menú</a>
      </div>

      <!-- FORMULARIO DE REGISTRO Y EDICIÓN DE PROVEEDORES -->
      <!-- id="providers-form" será manejado por JavaScript (app.js) -->
      <form id="providers-form" class="card" style="margin-top:12px" autocomplete="off">
        <input type="hidden" name="id">

          <!-- Contenedor en dos columnas para los campos del formulario -->
        <div class="form-2-cols">
            <div class="form-field">

              <!-- Campos: Nombre, categoría, distrito, precio, reputación, experiencia-->
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Escriba el nombre" required>
            </div>

            <div class="form-field">
                <label for="categoria">Categoría</label>
                <input type="text" id="categoria" name="categoria" placeholder="Ej: Matrimonio, Cumpleaños" required>
            </div>

            <div class="form-field">
                <label for="distrito">Distrito</label>
                <input type="text" id="distrito" name="distrito" placeholder="Ej: Miraflores" required>
            </div>

            <div class="form-field">
                <label for="precio">Precio</label>
                <input type="number" step="0.01" id="precio" name="precio" placeholder="0.00" required>
            </div>

            <div class="form-field">
                <label for="reputacion">Reputación</label>
                <input type="number" step="0.1" min="0" max="10" id="reputacion" name="reputacion" placeholder="4.5">
            </div>

            <div class="form-field">
                <label for="experiencia">Experiencia (años)</label>
                <input type="number" id="experiencia" name="experiencia" placeholder="0">
            </div>
        </div>


        <!-- Botones de acción del formulario -->
        <div class="controls" style="margin-top:20px;">
            <button class="btn" type="submit">Guardar Proveedor</button>
            <button type="button" id="btn-reset" class="btn ghost">Limpiar</button>
        </div>
      </form>

         <!-- TABLA PARA LISTAR PROVEEDORES -->
      <div class="card table-wrap" style="margin-top:10px">
        <table id="providers-table" class="styled-table" aria-describedby="providers-desc">
          <caption id="providers-desc" class="visually-hidden">Tabla de proveedores</caption>
          <thead>
            <tr>
              <th>ID</th><th>Nombre</th><th>Categoria</th><th>Distrito</th><th>Precio</th><th>Reputacion</th><th>Experiencia</th><th></th><th></th>
            </tr>
          </thead>
          <tbody><tr><td colspan="9">Cargando...</td></tr></tbody>
        </table>
      </div>
    </section>
  </main>
</body>
</html>