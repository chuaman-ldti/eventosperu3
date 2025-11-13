<?php
require_once __DIR__ . '/../models/User.php';

// Iniciar sesión para acceder a la variable 'username' si existe
session_start();

$message = '';
$errors = []; // Array para guardar múltiples errores

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validación 1: Campos no vacíos
    if (empty($username) || empty($password)) {
        $errors[] = "⚠️ Debes completar todos los campos.";
    }

    // Validación 2: Longitud de la contraseña
    if (strlen($password) <= 8) {
        $errors[] = "⚠️ La contraseña debe tener más de 8 dígitos.";
    }

    // Validación 3: Contraseña alfanumérica
    // Comprueba si la contraseña contiene al menos una letra y un número.
    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors[] = "⚠️ La contraseña debe ser alfanumérica (contener letras y números).";
    }

    // Si no hay errores, procede a crear el usuario
    if (empty($errors)) {
        $userModel = new User();
        if ($userModel->createUser($username, $password)) {
            // Recomiendo redirigir al login o al menú principal después del éxito
            $message = "✅ Usuario creado correctamente.";
        } else {
            $errors[] = "❌ Error al crear el usuario. El usuario podría ya existe.";
        }
    }
}

// Variables para la cabecera (necesitas definir $current_page si quieres el 'active')
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="../assets/style.css">
    
    <style>
        body {
            /* Desactiva el display: flex del style.css para usar la cabecera */
            display: block; 
            min-height: 100vh; /* Asegura que el cuerpo ocupe toda la altura */
        }
        .container.signup-page {
            /* Centraliza el formulario debajo de la cabecera */
            display: flex;
            justify-content: center;
            padding: 30px 18px;
            /* Usa max-width: 1100px para centrar el formulario si es necesario */
        }
        /* Ajuste para el formulario de autenticación/registro */
        #signupForm {
            width: 100%;
            max-width: 380px; /* Ancho máximo para el formulario */
            margin: 0 auto;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow); /* Usar la sombra del CSS global */
            background: var(--card); /* Fondo blanco/claro */
        }
        #signupForm h2 {
            margin-top: 0;
            color: var(--text-main);
        }
        #signupForm input {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #CFD8DC; /* Borde suave */
            box-sizing: border-box;
        }
        #signupForm button[type="submit"] {
            width: 100%;
            display: block;
        }
        .msg {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-weight: 600;
        }
        .msg.success {
            color: #008800;
            background: #e6ffe6;
            border: 1px solid #00aa00;
        }
        .msg.error {
            color: #cc0000;
            background: #ffe6e6;
            border: 1px solid #cc0000;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="brand">
            <div class="logo">EP</div>
            <div>
                <h1>Eventos Perú</h1>
                <div style="font-size:13px;opacity:0.9">Gestión de Usuarios</div>
            </div>
        </div>
        
        <nav class="main-nav">
            </nav>
        
        <div class="user-info">
        
        <span class="welcome-text">Bienvenido **<?php echo htmlspecialchars($_SESSION['username']); ?>** 👨‍💻</span>
        
        <a href="logout.php" class="logout-link">Cerrar Sesión</a>
    </div>
    </header>
        <div style="
         text-align:center;
         margin-top: 35px;
         margin-bottom: -10px;">

      <a href="menu.php" 
           style="
           font-size: 22px;
           font-weight: 600;
          color: #263238;
          text-decoration: none;">
           ← Volver al Panel Principal
         </a>
        </div>

    <main class="container signup-page">

    <form id="signupForm" method="post" action="" color="#263238">

        <h2 style="margin-top:0;">Registrar nuevo usuario</h2>

            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="msg error"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($message): ?>
                <div class="msg success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <input type="text" name="username" placeholder="Usuario" value="<?= htmlspecialchars($username ?? '') ?>" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" class="btn">Registrar</button>
            
        </form>
    </main>
    <script src="../assets/app.js"></script>
</body>
</html>