<?php
require 'database/conexion.php';
session_start();

// Obtener datos de insulina y glucosa
$idUsuario = $_SESSION['usuario_id'];

// Obtener datos de insulina
$queryInsulina = "SELECT fecha, SUM(insulina) as total_insulina FROM comidas WHERE idUsuario = :idUsuario GROUP BY fecha ORDER BY fecha";
$stmtInsulina = $pdo->prepare($queryInsulina);
$stmtInsulina->execute(['idUsuario' => $idUsuario]);
$insulinaData = $stmtInsulina->fetchAll(PDO::FETCH_ASSOC);

// Obtener datos de glucosa
$queryGlucosa = "SELECT fecha, AVG(glucosa_pre) as avg_glucosa_pre, AVG(glucosa_post) as avg_glucosa_post FROM comidas WHERE idUsuario = :idUsuario GROUP BY fecha ORDER BY fecha";
$stmtGlucosa = $pdo->prepare($queryGlucosa);
$stmtGlucosa->execute(['idUsuario' => $idUsuario]);
$glucosaData = $stmtGlucosa->fetchAll(PDO::FETCH_ASSOC);

// Preparar datos para los gráficos
$fechas = [];
$totalInsulina = [];
$avgGlucosaPre = [];
$avgGlucosaPost = [];

foreach ($insulinaData as $data) {
    $fechas[] = $data['fecha'];
    $totalInsulina[] = $data['total_insulina'];
}

foreach ($glucosaData as $data) {
    $avgGlucosaPre[] = $data['avg_glucosa_pre'];
    $avgGlucosaPost[] = $data['avg_glucosa_post'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .chart-container {
            width: 100%;
            max-width: 600px;
            margin: auto;
            background-color: white; /* Fondo blanco para los gráficos */
            padding: 20px; /* Espaciado interno */
            border-radius: 8px; /* Bordes redondeados */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra para un efecto de elevación */
        }
    </style>
</head>
<body>
<div class="container-fluid" style="background: linear-gradient(to right, #007bff, #ff4d4d) !important; min-height: 100vh; display: flex; flex-direction: column;">
    <nav class="navbar fixed-top bg-body-tertiary py-3">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-2">
                <a href="dashboard.php" class="text-decoration-none text-dark d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="me-2" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="fs-5 me-5 ms-2"><b>Home <<</b></span>
                </a>
            </div>
            <div class="d-flex justify-content-center align-items-center flex-grow-1">
                <h2 class="fs-1 mx-4"><b>GLUCOTRACK</b></h2>
                <img src="./imagenes_video/logoPrincipal.jpg" alt="Logo Principal" class="img-fluid rounded" style="width: 50px; height: auto;">
            </div>
        </div>
    </nav>

    <div class="mt-5 pt-5">
        <h2 class="text-center">Estadísticas de Insulina y Glucosa</h2>
        
        <!-- Gráfico de línea para la dosis de insulina -->
        <div class="chart-container mt-5">
            <canvas id="insulinaChart" class="mt-5"></canvas>
        </div>
        
        <!-- Gráfico de barras para los niveles de glucosa -->
        <div class="chart-container mt-5">
            <canvas id="glucosaChart"></canvas>
        </div>
        
        <script>
            var fechas = <?php echo json_encode($fechas); ?>;
            var totalInsulina = <?php echo json_encode($totalInsulina); ?>;
            var avgGlucosaPre = <?php echo json_encode($avgGlucosaPre); ?>;
            var avgGlucosaPost = <?php echo json_encode($avgGlucosaPost); ?>;
            
            // Gráfico de línea para la dosis de insulina
            var ctxInsulina = document.getElementById('insulinaChart').getContext('2d');
            var insulinaChart = new Chart(ctxInsulina, {
                type: 'line',
                data: {
                    labels: fechas,
                    datasets: [{
                        label: 'Dosis de Insulina',
                        data: totalInsulina,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false
                    }]
                }
            });
            
            // Gráfico de barras para los niveles de glucosa
            var ctxGlucosa = document.getElementById('glucosaChart').getContext('2d');
            var glucosaChart = new Chart(ctxGlucosa, {
                type: 'bar',
                data: {
                    labels: fechas,
                    datasets: [
                        {
                            label: 'Glucosa Pre',
                            data: avgGlucosaPre,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Glucosa Post',
                            data: avgGlucosaPost,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }
                    ]
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>