<?php
require_once __DIR__ . '/../models/User.php';
session_start();

// -----------------------------------------------------------------
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: menu.php"); 
    exit;
}
// -----------------------------------------------------------------

$message = '';
$errors = []; 
$username_form = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? 'user'); 
    
    $username_form = $username; 


//----------------------------------------------------------------------
    //Existen 4 validaciones
    
    if (empty($username) || empty($password)) {
        $errors[] = "Debes completar todos los campos.";
    }

    if (strlen($password) <= 8) {
        $errors[] = "La contraseña debe tener más de 8 dígitos.";
    }

    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors[] = "La contraseña debe ser alfanumérica (contener letras y números).";
    }
    
    if (!in_array($role, ['user', 'admin'])) {
         $errors[] = "Rol no válido.";
    }

//--------------------------------------------------------------------

    if (empty($errors)) {
        $userModel = new User();
        
        if ($userModel->findByUsername($username)) {
            $errors[] = "Error: El nombre de usuario '{$username}' ya existe.";
        } else {

            if ($userModel->createUser($username, $password, $role)) {
                $message = "Usuario '{$username}' creado correctamente con el rol '{$role}'.";
                $username_form = ''; 
            } else {
                $errors[] = "Error desconocido al crear el usuario.";
            }
        }
    }
}


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
            display: block; 
            min-height: 100vh;
        }
        .container.signup-page {
            display: flex;
            justify-content: center;
            padding: 30px 18px;
        }
        #signupForm {
            width: 100%;
            max-width: 380px; 
            margin: 0 auto;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            background: var(--card); 
        }
        #signupForm h2 {
            margin-top: 0;
            color: var(--text-main);
        }

        #signupForm input,
        #signupForm select {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #CFD8DC; 
            box-sizing: border-box;
            background: #FFF; 
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


            <input type="text" name="username" placeholder="Usuario" value="<?= htmlspecialchars($username_form) ?>" required>
            <input type="password" name="password" placeholder="Contraseña" required>


            <label for="role" style="display:block; margin-bottom: 5px; color: #555; font-size: 0.9em;">Rol del nuevo usuario:</label>
            <select name="role" id="role" required>
                <option value="user" selected>Usuario (User)</option>
                <option value="admin">Administrador (Admin)</option>
            </select>
            

            <button type="submit" class="btn">Registrar</button>
            
        </form>
    </main>
    <script src="../assets/app.js"></script>
</body>
</html>