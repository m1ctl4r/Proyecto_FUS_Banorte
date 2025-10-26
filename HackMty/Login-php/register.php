<?php
session_start();

$error_registro = "";
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'password') {
        $error_registro = "Las contraseñas no coinciden.";
    } else if ($_GET['error'] == 'usuario_existe') {
        $error_registro = "Ese nombre de usuario ya está en uso.";
    } else if ($_GET['error'] == 'campos_vacios') {
        $error_registro = "Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Plataforma Banorte</title>
    <link rel="stylesheet" href="../CSS/login.css"> 
    <link rel="icon" href="../Iconos/FUS.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

    <div class="login-container">
        
       <div class="logo">
            <a href="../index.html">
             <img src="../Iconos/Banorte 1.jpg" alt="Logo Banorte"> 
            </a>
        </div>


        <h2>Crear una Cuenta</h2>

        <?php 
        if (!empty($error_registro)) {
            echo '<div class="error-message">' . $error_registro . '</div>';
        }
        ?>

        <form action="procesar_registro.php" method="post">
            
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="usuario" placeholder="Elige un nombre de usuario" required>
            </div> 
            
            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="password" placeholder="Crea una contraseña" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="confirmar_password" placeholder="Confirma tu contraseña" required>
            </div>

            <div class="button-group">
                <input type="submit" value="Crear Cuenta">
            </div>
        </form>

        <div class="register-link">
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
    </div>

</body>
</html>