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

// Definimos $current_page (aunque parece que no se usa en el nav de signup.php)
// Pero es buena práctica tenerla si la cabecera es un include.
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="utf-8">
        <title>Eventos Perú</title>
        <?php
            $cssPath = __DIR__ . '/../assets/style.css';
            $cssVer = file_exists($cssPath) ? filemtime($cssPath) : time();
        ?>
        <link rel="stylesheet" href="../assets/style.css?v=<?php echo $cssVer; ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <script src="../assets/app.js" defer></script>
        </head>
<body>
    <header class="header">
        <div class="brand">
            <div class="logo">EP</div>
            <div>
                <h1>Eventos Perú</h1>
                <div style="font-size:13px;opacity:0.9">Gestión de Eventos</div>
            </div>
        </div>
        
        <nav class="main-nav">
            <a href="menu.php" class="<?php echo ($current_page == 'menu.php') ? 'active' : ''; ?>">Menu</a>
            <a href="clients.php" class="<?php echo ($current_page == 'clients.php') ? 'active' : ''; ?>">Clientes</a>
            <a href="providers.php" class="<?php echo ($current_page == 'providers.php') ? 'active' : ''; ?>">Proveedores</a>
            <a href="events.php" class="<?php echo ($current_page == 'events.php') ? 'active' : ''; ?>">Programacion</a>
        </nav>
        
        <div class="user-info">
            <span class="welcome-text">Bienvenido <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?> 👨‍💻</span>
            <a href="logout.php" class="logout-link">Cerrar Sesión</a>
        </div>
    </header>

    <main class="container">
        
        <h2 style="margin-top:0">Panel de Administración</h2>
        <p style="margin-bottom: 30px; color:var(--muted);">Usa las tarjetas para gestionar clientes, proveedores y la programación de eventos.</p>
        
        <div class="dashboard-grid">
            
            <a href="clients.php" class="dashboard-card">
                <div class="icon-box">
                    <i class="fas fa-users"></i> 
                </div>
                <h2>Gestión de Clientes</h2>
                <p>Ver, crear y editar la base de datos de clientes.</p>
            </a>
            
            <a href="providers.php" class="dashboard-card">
                <div class="icon-box">
                    <i class="fas fa-building"></i>
                </div>
                <h2>Gestión de Proveedores</h2>
                <p>Administra los datos de los proveedores de servicios.</p>
            </a>
            
            <a href="events.php" class="dashboard-card">
                <div class="icon-box">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h2>Programación de Eventos</h2>
                <p>Controla las fechas, ubicaciones y estados de los eventos.</p>
            </a>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="signup.php" class="dashboard-card">
                <div class="icon-box">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2>Registro de Usuarios</h2>
                <p>Crea nuevas cuentas para el personal de administración.</p>
            </a>
            <?php endif; ?> 
            </div>
        <div class="footer">© Grupo 10 - JavaTeam</div>
    </main>
</body>
</html>