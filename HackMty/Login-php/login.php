<?php

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('location: dashboard.php');
    exit;
}

$error_login = "";
$success_message = "";

if (isset($_GET['error']) && $_GET['error'] == 1) {
    $error_login = "Usuario o contraseña incorrectos.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Plataforma Banorte</title>
    <link rel="stylesheet" href="../CSS/login.css">
    <link rel="icon" href="../Iconos/FUS.png" type="image/x-icon">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <div id="toast-notification" class="toast">
        ¡Registro exitoso! Ya puedes iniciar sesión.
    </div>

    <div class="login-container">
        
        <div class="logo">
            <a href="../index.html">
             <img src="../Iconos/Banorte 1.jpg" alt="Logo Banorte"> 
            </a>
        </div>

        <h2>Inicia Sesión en Plataforma</h2>

        <?php 
        if (!empty($error_login)) {
            echo '<div class="error-message">' . $error_login . '</div>';
        }
        
        ?>

        <form action="validar_login.php" method="post">
            
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="usuario" placeholder="Usuario o Correo Electrónico" required>
            </div> 
            
            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>

            <div class="options">
                <label class="checkbox-label">
                    <input type="checkbox" name="recordar">
                    Recordar mi usuario
                </label>
                </div>

            <div class="button-group">
                <input type="submit" value="Iniciar Sesión">
            </div>
        </form>

        <div class="register-link">
            <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
        </div>
    </div>
    <script>
        if (window.location.search.includes('registro=exitoso')) {
            const notification = document.getElementById('toast-notification');

            setTimeout(() => {
                notification.classList.add('show');
            }, 100); 

            setTimeout(() => {
                notification.classList.remove('show');
            }, 4000); 
        }
    </script>

</body>
</html>