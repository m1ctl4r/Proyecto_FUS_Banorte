<?php
session_start();

// Incluir el archivo de conexión
include 'Login-php/conexion.php';

// 1. Verificar si el usuario está logueado
// Esta línea ahora funcionará porque validar_login.php ya guarda 'user_id'
if (!isset($_SESSION['user_id'])) { 
    header('Location: Login-php/login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$mensaje_exito = "";

// 2. Lógica para procesar pagos (CUANDO SE ENVÍA UN FORMULARIO)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Si se presionó "Pagar Todo"
    if (isset($_POST['pagar_todo'])) {
        $sql_pagar_todo = "UPDATE consumos SET pagado = 1 WHERE user_id = ? AND pagado = 0";
        if ($stmt = $conn->prepare($sql_pagar_todo)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
            $mensaje_exito = "¡Todos los recibos han sido pagados!";
        }
    }
    
    // Si se presionó "Pagar" en un recibo individual
    if (isset($_POST['pagar_recibo'])) {
        $consumo_id = $_POST['consumo_id'];
        $sql_pagar_uno = "UPDATE consumos SET pagado = 1 WHERE consumo_id = ? AND user_id = ?";
        if ($stmt = $conn->prepare($sql_pagar_uno)) {
            $stmt->bind_param("ii", $consumo_id, $user_id);
            $stmt->execute();
            $stmt->close();
            $mensaje_exito = "¡Recibo pagado con éxito!";
        }
    }
}

// 3. Obtener todos los recibos PENDIENTES (pagado = 0)
$recibos_pendientes = [];
$sql_pendientes = "SELECT c.consumo_id, s.nombre_servicio, c.fecha, c.monto 
                   FROM consumos c
                   JOIN servicios s ON c.servicio_id = s.servicio_id
                   WHERE c.user_id = ? AND c.pagado = 0
                   ORDER BY c.fecha ASC";

if ($stmt_pendientes = $conn->prepare($sql_pendientes)) {
    $stmt_pendientes->bind_param("i", $user_id);
    $stmt_pendientes->execute();
    $result_pendientes = $stmt_pendientes->get_result();
    while ($row = $result_pendientes->fetch_assoc()) {
        $recibos_pendientes[] = $row;
    }
    $stmt_pendientes->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Verde - FUS Banorte</title>
    <link rel="stylesheet" href="CSS/dashboard.css"> </head>
        <link rel="icon" href="Iconos/FUS.png" type="image/x-icon">
<body>

    <div class="container">
           <nav class="main-nav">
    <div class="nav-brand">
     <img src="Iconos/Banorte 1.jpg" alt="Logo Banorte" class="banorte-logo">
    <img src="Iconos/FUS.png" alt="Logo FUS" class="fus-logo">
        
    </div>
    <ul class="nav-links">
        <li><a href="dashboard.php" class="nav-link-item">Dashboard</a></li>
        <li><a href="cuenta_verde.php" class="nav-link-item">Cuenta Verde</a></li>
        <li><a href="ahorro_inversion.php" class="nav-link-item">Ahorro e Inversión</a></li>
        <li><a href="simulador_banco.php" class="nav-link-item">Visión Banco</a></li>
        <li><a href="Login-php/logout.php" class="nav-link-item">Cerrar Sesión</a></li>
    </ul>
</nav>

        <h1>Cuenta Verde (Pagos Centralizados)</h1>
        
        <?php if ($mensaje_exito): ?>
            <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
        <?php endif; ?>

        <?php if (empty($recibos_pendientes)): ?>
            <p>¡Felicidades! No tienes ningún recibo pendiente.</p>
        <?php else: ?>
            
            <form action="cuenta_verde.php" method="POST">
                <button type="submit" name="pagar_todo" class="btn-pagar-todo">Pagar Todos los Recibos (<?php echo count($recibos_pendientes); ?>)</button>
            </form>

            <ul class="recibo-lista">
                <?php foreach ($recibos_pendientes as $recibo): ?>
                    <li class="recibo-item">
                        <div class="recibo-info">
                            <strong><?php echo htmlspecialchars($recibo['nombre_servicio']); ?></strong>
                            <br>
                            <small>Vencimiento: <?php echo date("d/m/Y", strtotime($recibo['fecha'] . "+15 days")); ?></small>
                        </div>
                        <div class="recibo-monto">$<?php echo number_format($recibo['monto'], 2); ?></div>
                        
                        <form action="cuenta_verde.php" method="POST" style="margin:0;">
                            <input type="hidden" name="consumo_id" value="<?php echo $recibo['consumo_id']; ?>">
                            <button type="submit" name="pagar_recibo" class="btn">Pagar</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</body>
</html>