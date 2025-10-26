<?php
$servidor = "localhost"; 
$usuario_db = "root";    
$password_db = "";       
$nombre_db = "HackMTy"; 


$conn = new mysqli($servidor, $usuario_db, $password_db, $nombre_db);


if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>