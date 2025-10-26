document.getElementById('formSimulacion').addEventListener('submit', function(e) {
    e.preventDefault();

    // 1. Obtener valores del formulario
    const promedio = parseFloat(document.getElementById('gastoPromedio').value);
    const actual = parseFloat(document.getElementById('gastoActual').value);
    const rendimientoAnual = parseFloat(document.getElementById('rendimientoFondo').value) / 100; // Convertir a decimal

    // 2. Lógica de Negocio: Calcular el 'Ahorro por Sostenibilidad' [cite: 15]
    let ahorroMensual = 0;
    
    // El sistema compara si el gasto actual es menor que el promedio [cite: 19]
    if (actual < promedio) {
        ahorroMensual = promedio - actual;
    }

    // 3. Simulación de Inversión (Interés Simple Acumulado en 12 meses)
    const meses = 12;
    // Rendimiento simple para fines de simulación didáctica
    const rendimientoMensual = rendimientoAnual / 12; 

    // Ahorro total sin interés: Ahorro * 12
    const ahorroAcumulado = ahorroMensual * meses; 
    
    // Cálculo simplificado del valor futuro (Interés Compuesto simplificado para 1 año)
    // VF = P * [(1 + r/n)^(nt) - 1] / (r/n) * (1 + r/n)
    // Donde P es el pago periódico, r la tasa anual, n los periodos por año, t el tiempo en años.
    let valorFuturo = 0;
    
    // Usaremos un cálculo simplificado de interés simple para la simulación:
    // Ahorro total + Intereses (ej. asumiendo el ahorro se invierte al inicio del mes)
    // El cálculo real del fondo es más complejo (diario y compuesto).
    
    // Asumiendo que el ahorro de cada mes genera un rendimiento promedio del rendimiento anual.
    // Un cálculo más preciso de la suma de series compuestas es demasiado complejo para el frontend.
    valorFuturo = ahorroAcumulado * (1 + (rendimientoAnual / 2)); // Estimación simple

    // 4. Mostrar Resultados
    document.getElementById('ahorroMensual').textContent = `$${ahorroMensual.toFixed(2)}`;
    document.getElementById('proyeccion12Meses').textContent = `$${(ahorroAcumulado * (1 + rendimientoAnual)).toFixed(2)} MXN`; // Usamos una fórmula sencilla para el ejemplo

    document.getElementById('resultados').style.display = 'block';
});
