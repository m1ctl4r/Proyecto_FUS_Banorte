<?php
session_start();

require_once "conexion.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $usuario = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);
    
    $sql = "SELECT id, usuario, password FROM usuarios WHERE usuario = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $param_usuario);
        
        $param_usuario = $usuario;
        
        if ($stmt->execute()) {
            $stmt->store_result();
            
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $usuario_db, $password_hash);
                
                if ($stmt->fetch()) {
                    if (password_verify($password, $password_hash)) {
                        $_SESSION['loggedin'] = true;
                        
                        // --- ESTE ES EL CAMBIO ---
                        $_SESSION['user_id'] = $id; 
                        // -------------------------

                        $_SESSION['usuario'] = $usuario_db;
                        
                        header("location: ../dashboard.php");
                        exit; 
                    } else {
                        header("location: login.php?error=1");
                        exit;
                    }
                }
            } else {
                header("location: login.php?error=1");
                exit;
            }
        } else {
            echo "Oops! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
        }
        
        $stmt->close();

    } else {
         echo "Oops! Hubo un error preparando la consulta.";
    }
    
    $conn->close();

} else {
    header("location: login.php");
    exit;
}
?>