<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
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
                <li><a href="ahorro_inversion.php" class="nav-link-item active">Ahorro e Inversión</a></li>
                <li><a href="simulador_banco.php" class="nav-link-item">Visión Banco</a></li>
                <li><a href="Login-php/logout.php" class="nav-link-item">Cerrar Sesión</a></li>
            </ul>
        </nav>

        <h1>Simulador de Crédito Verde</h1>
        
        <div class="card simulador-container">
            <p>Calcula el pago mensual de un crédito para paneles solares.</p>
            
            <div class="form-group">
                <label for="monto">Monto del Crédito ($)</label>
                <input type="number" id="monto" value="150000">
            </div>

            <div class="form-group">
                <label for="plazo">Plazo (en meses)</label>
                <input type="number" id="plazo" value="48">
            </div>

            <div class="form-group">
                <label for="tasa">Tasa de Interés Anual (%)</label>
                <input type="number" id="tasa" value="10.49" step="0.01">
            </div>

            <button class="btn-calcular" onclick="calcularCredito()">Calcular</button>

            <div class="resultados-container">
                <div class="resultado-item">
                    Pago Mensual Estimado:
                    <span id="pago_mensual">$0.00</span>
                </div>
                <div class="resultado-item">
                    Total de Intereses:
                    <span id="interes_total">$0.00</span>
                </div>
                <div class="resultado-item">
                    Pago Total (Capital + Interés):
                    <span id="pago_total">$0.00</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calcularCredito() {
            // 1. Obtener los valores de los inputs
            const monto = parseFloat(document.getElementById('monto').value);
            const plazo = parseInt(document.getElementById('plazo').value);
            const tasa_anual = parseFloat(document.getElementById('tasa').value);

            // 2. Validar que los números sean correctos
            if (isNaN(monto) || isNaN(plazo) || isNaN(tasa_anual) || monto <= 0 || plazo <= 0 || tasa_anual <= 0) {
                alert("Por favor, ingresa valores válidos y positivos.");
                return;
            }

            // 3. Calcular
            const tasa_mensual = (tasa_anual / 100) / 12;
            
            // Fórmula de amortización (pago mensual)
            const pago_mensual = monto * (tasa_mensual * Math.pow(1 + tasa_mensual, plazo)) / (Math.pow(1 + tasa_mensual, plazo) - 1);
            
            const pago_total = pago_mensual * plazo;
            const interes_total = pago_total - monto;

            // 4. Formatear los números como moneda (MXN)
            const formatter = new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: 'MXN'
            });

            // 5. Mostrar los resultados en el HTML
            document.getElementById('pago_mensual').innerText = formatter.format(pago_mensual);
            document.getElementById('interes_total').innerText = formatter.format(interes_total);
            document.getElementById('pago_total').innerText = formatter.format(pago_total);
        }

        // Opcional: Calcular al cargar la página por primera vez con los valores por defecto
        document.addEventListener('DOMContentLoaded', calcularCredito);
    </script>

</body>
</html>