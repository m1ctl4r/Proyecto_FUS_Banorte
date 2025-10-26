<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /Login/login.php');
    exit;
}

// 1. Obtener el tipo de acción desde la URL (ej. ...?tipo=inversion)
$tipo = $_GET['tipo'] ?? 'default'; // 'default' si no se especifica
$monto = $_GET['monto'] ?? 0;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahorro e Inversión - FUS Banorte</title>
    <link rel="stylesheet" href="CSS/dashboard.css">
        <link rel="icon" href="Iconos/FUS.png" type="image/x-icon">
</head>
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

        <div class="ahorro-container">
            
            <?php // 2. Lógica para mostrar contenido dinámico ?>

            <?php if ($tipo == 'inversion'): ?>
                <h1>¡Invierte tu Ahorro!</h1>
                <p>¡Felicidades! Notamos que ahorraste <strong>$<?php echo number_format($monto, 2); ?></strong> este mes.</p>
                <p>En lugar de dejarlo en tu cuenta, ¡haz que crezca de forma sostenible!</p>
                <br>
                <a href="#" class="btn" onclick="alert('Simulación: Solicitud de inversión enviada.')">Invertir en Fondo Verde Banorte</a>
                <a href="dashboard.php" class="btn btn-secondary">Regresar</a>

            <?php elseif ($tipo == 'financiamiento'): ?>
                <h1>Financiamiento Verde</h1>
                <p>Detectamos un aumento en tu consumo de energía.</p>
                <p>Reduce tus gastos futuros y ayuda al planeta invirtiendo en tecnologías limpias como paneles solares.</p>
                <br>
                <a href="credito.php" class="btn" onclick="alert('Simulación: Solicitud de crédito pre-aprobada.')">Simular Crédito para Paneles Solares</a>
                

            <?php else: ?>
                <h1>Ahorro e Inversión Sostenible</h1>
                <p>Descubre cómo FUS Banorte te ayuda a manejar tus finanzas mientras cuidas el planeta.</p>
                <p>Desde aquí podrás acceder a inversiones verdes y financiamiento para ecotecnologías.</p>
                <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>