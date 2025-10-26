<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit;
}

$clientes_simulados = [
    ['id' => 1, 'nombre' => 'Cliente A (Usuario Actual)'],
    ['id' => 2, 'nombre' => 'Cliente B'],
    ['id' => 3, 'nombre' => 'Cliente C'],
    ['id' => 4, 'nombre' => 'Cliente D'],
    ['id' => 5, 'nombre' => 'Cliente E'],
    ['id' => 6, 'nombre' => 'Cliente F'],
    ['id' => 7, 'nombre' => 'Cliente G']
];

$reporte_banco = [];
$ganancia_total_banco = 0;
$total_con_credito = 0;

// NUEVO: Variables para la gráfica de pie
$total_comisiones = 0;
$total_intereses = 0;

// 3. Datos del producto de crédito (basado en tu simulador)
$tasa_anual_credito = 0.1049; // 10.49%
$monto_credito = 150000;
$interes_mensual_credito = $monto_credito * ($tasa_anual_credito / 12);

// 4. Procesar cada cliente simulado
foreach ($clientes_simulados as $cliente) {
    
    $comision_cuenta = 120.50; 
    $interes_credito = 0;
    
    // Simulemos que los clientes 1, 3 y 6 SÍ tienen el crédito verde
    if ($cliente['id'] == 1 || $cliente['id'] == 3 || $cliente['id'] == 6) {
        $interes_credito = $interes_mensual_credito;
        $total_con_credito++;
    }
    
    $ganancia_por_cliente = $comision_cuenta + $interes_credito;
    
    $ganancia_total_banco += $ganancia_por_cliente;
    $total_comisiones += $comision_cuenta; 
    $total_intereses += $interes_credito;  
    
    $reporte_banco[] = [
        'nombre' => $cliente['nombre'],
        'comision' => $comision_cuenta,
        'interes' => $interes_credito,
        'total' => $ganancia_por_cliente
    ];
}

$total_clientes = count($clientes_simulados);
$ganancia_promedio = $ganancia_total_banco / $total_clientes;

// NUEVO: Cálculo de Tasa de Adopción
$tasa_adopcion = ($total_con_credito / $total_clientes) * 100;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visión Banco - FUS Banorte</title>
     <link rel="stylesheet" href="CSS/dashboard.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        nav a.active {
            font-weight: bold;
            text-decoration: underline;
        }
        /* AHORA 4 COLUMNAS PARA LOS KPIs */
        .dashboard-grid { 
            grid-template-columns: repeat(4, 1fr);
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .report-table th, .report-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .report-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .report-table td.monto {
            text-align: right;
            font-family: monospace;
            font-size: 1.1em;
        }
        
        .chart-container {
            position: relative;
            margin: auto;
            height: 300px; 
            max-width: 350px; 
        }
    </style>
</head>
<body>

    <div class="container">
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
        <h1>Visión del Banco</h1>
        
        <div class="dashboard-grid">
            <div class="card">
                <div class="card-title">Ganancia Total (Simulada)</div>
                <div class="card-monto">$<?php echo number_format($ganancia_total_banco, 2); ?></div>
            </div>
            <div class="card">
                <div class="card-title">Total de Clientes</div>
                <div class="card-monto"><?php echo $total_clientes; ?></div>
            </div>
            <div class="card">
                <div class="card-title">Clientes con Crédito Verde</div>
                <div class="card-monto"><?php echo $total_con_credito; ?></div>
            </div>
            <div class="card">
                <div class="card-title">Tasa de Adopción (Crédito)</div>
                <div class="card-monto"><?php echo number_format($tasa_adopcion, 1); ?>%</div>
            </div>
        </div>

        <div class="dashboard-grid" style="grid-template-columns: 2fr 1fr; margin-top: 25px;">
            <div class="card">
                <h3>Reporte de Ganancia por Cliente (Mensual)</h3>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Comisión</th>
                            <th>Interés</th>
                            <th>Ganancia Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reporte_banco as $reporte): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reporte['nombre']); ?></td>
                                <td class="monto">$<?php echo number_format($reporte['comision'], 2); ?></td>
                                <td class="monto">$<?php echo number_format($reporte['interes'], 2); ?></td>
                                <td class="monto"><strong>$<?php echo number_format($reporte['total'], 2); ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h3>Fuentes de Ingreso</h3>
                <div class="chart-container">
                    <canvas id="gananciasPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('gananciasPieChart').getContext('2d');
        const gananciasPieChart = new Chart(ctx, {
            type: 'pie', 
            data: {
                labels: [
                    'Comisiones', 
                    'Intereses (Créditos)' 
                ],
                datasets: [{
                    label: 'Fuentes de Ingreso',
                    data: [
                        <?php echo json_encode($total_comisiones); ?>, 
                        <?php echo json_encode($total_intereses); ?>  
                    ],
                    backgroundColor: [
                        'rgba(230, 0, 46, 0.7)', 
                        'rgba(0, 102, 51, 0.7)'  
                    ],
                    borderColor: [
                        'rgba(230, 0, 46, 1)',
                        'rgba(0, 102, 51, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, 
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>

</body>
</html>