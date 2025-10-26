<?php
session_start();
include 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmar_password = mysqli_real_escape_string($conn, $_POST['confirmar_password']);

    if (empty($usuario) || empty($password) || empty($confirmar_password)) {
        header("location: register.php?error=campos_vacios");
        exit;
    }

    if ($password != $confirmar_password) {
       header("location: login.php?registro=exitoso&");
        exit;
    }

    $sql_check = "SELECT id FROM usuarios WHERE usuario = ?";
    
    if ($stmt_check = mysqli_prepare($conn, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "s", $usuario);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);
        
        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            header("location: register.php?error=usuario_existe");
        } else {

            $password_encriptada = password_hash($password, PASSWORD_DEFAULT);

            $sql_insert = "INSERT INTO usuarios (usuario, password) VALUES (?, ?)";
            
            if ($stmt_insert = mysqli_prepare($conn, $sql_insert)) {
                mysqli_stmt_bind_param($stmt_insert, "ss", $usuario, $password_encriptada);
                
                if (mysqli_stmt_execute($stmt_insert)) {
                    header("location: login.php?registro=exitoso");
                } else {
                    echo "Algo salió mal. Inténtalo de nuevo.";
                }
                mysqli_stmt_close($stmt_insert);
            }
        }
        mysqli_stmt_close($stmt_check);
    }
        mysqli_close($conn);
}
?>