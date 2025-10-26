<?php
session_start();
include 'Login-php/conexion.php'; // Incluye la conexión a la BD

// 1. Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: Login-php/login.php'); // Redirige a tu login si no ha iniciado sesión
    exit;
}
$user_id = $_SESSION['user_id'];

// 2. Obtener datos para la gráfica de línea (últimos 12 meses)
$chart_labels = [];
$chart_data = [];

$sql_chart = "SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, SUM(monto) AS total 
              FROM consumos 
              WHERE user_id = ? AND fecha >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
              GROUP BY mes 
              ORDER BY mes ASC";
if ($stmt_chart = $conn->prepare($sql_chart)) {
    $stmt_chart->bind_param("i", $user_id);
    $stmt_chart->execute();
    $result_chart = $stmt_chart->get_result();
    while ($row = $result_chart->fetch_assoc()) {
        $chart_labels[] = $row['mes'];
        $chart_data[] = $row['total'];
    }
    $stmt_chart->close();
}

// 3. Obtener datos para las tarjetas de resumen y alertas
$servicios_resumen = [];
$sql_servicios = "SELECT servicio_id, nombre_servicio FROM servicios";
$result_servicios = $conn->query($sql_servicios);

while ($servicio = $result_servicios->fetch_assoc()) {
    $servicio_id = $servicio['servicio_id'];
    
    // Prepara la consulta para obtener los últimos 2 recibos de este servicio
    $sql_recibos = "SELECT monto FROM consumos 
                    WHERE user_id = ? AND servicio_id = ? 
                    ORDER BY fecha DESC 
                    LIMIT 2";
    
    if ($stmt_recibos = $conn->prepare($sql_recibos)) {
        $stmt_recibos->bind_param("ii", $user_id, $servicio_id);
        $stmt_recibos->execute();
        $result_recibos = $stmt_recibos->get_result();
        $recibos = $result_recibos->fetch_all(MYSQLI_ASSOC);
        
        $monto_actual = $recibos[0]['monto'] ?? 0;
        $monto_anterior = $recibos[1]['monto'] ?? $monto_actual; // Si no hay anterior, no hay diferencia
        $diferencia = $monto_actual - $monto_anterior;

        $servicios_resumen[] = [
            'nombre' => $servicio['nombre_servicio'],
            'monto_actual' => $monto_actual,
            'diferencia' => $diferencia
        ];
        $stmt_recibos->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard FUS Banorte</title>
    <link rel="stylesheet" href="CSS/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        <h1>Dashboard de Control</h1>

        <div class="card">
            <h3>Consumo Histórico (Últimos 12 Meses)</h3>
            <canvas id="consumoChart"></canvas>
        </div>
        
        <h2>Resumen de Servicios</h2>
        
        <div class="dashboard-grid">
            <?php foreach ($servicios_resumen as $resumen): ?>
                <div class="card">
                    <div class="card-title"><?php echo htmlspecialchars($resumen['nombre']); ?></div>
                    <div class="card-monto">$<?php echo number_format($resumen['monto_actual'], 2); ?></div>
                    
                    <?php
                    // Lógica de diferencia
                    $diferencia = $resumen['diferencia'];
                    if ($diferencia > 0) {
                        $diff_class = 'diff-positive';
                        $diff_text = sprintf("↑ $%.2f más que el mes pasado", $diferencia);
                    } elseif ($diferencia < 0) {
                        $diff_class = 'diff-negative';
                        $diff_text = sprintf("↓ $%.2f menos que el mes pasado", abs($diferencia));
                    } else {
                        $diff_class = '';
                        $diff_text = 'Sin cambios';
                    }
                    ?>
                    <div class="card-diff <?php echo $diff_class; ?>">
                        <?php echo $diff_text; ?>
                    </div>

                    <?php
                    // LÓGICA DE ALERTAS (El núcleo de tu idea)
                    if ($diferencia < -50) { // Ahorró más de $50
                        echo '<div class="alert alert-success">';
                        echo '¡Felicidades! Ahorraste. <a href="ahorro_inversion.php?tipo=inversion&monto=' . abs($diferencia) . '">Invierte tu ahorro</a>.';
                        echo '</div>';
                    } elseif ($diferencia > 100) { // Gastó más de $100
                        echo '<div class="alert alert-danger">';
                        echo 'Detectamos un aumento. <a href="ahorro_inversion.php?tipo=financiamiento">Conoce opciones de Financiamiento Verde</a>.';
                        echo '</div>';
                    }
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Configuración de la gráfica
        const ctx = document.getElementById('consumoChart').getContext('2d');
        const consumoChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>, // Meses desde PHP
                datasets: [{
                    label: 'Gasto Total Mensual',
                    data: <?php echo json_encode($chart_data); ?>, // Montos desde PHP
                    backgroundColor: 'rgba(230, 0, 46, 0.1)',
                    borderColor: 'rgba(230, 0, 46, 1)',
                    borderWidth: 2,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>