<?php
require 'database/conexion.php';
session_start();

$username = $_SESSION['usuario_username'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GlucoTrack - Menú Principal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
  <style>
   
    .menu-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .menu {
      width: 90%;
      max-width: 500px;
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      text-align: center;
    }
   
    .btn-grid a {
      
      background: #007bff;
      
    }
    .btn-grid a:hover {
      background: #0056b3;
      transform: scale(1.05);
    }
    .btn-grid a:nth-child(even) {
      background: #28a745;
    }
    .btn-grid a:nth-child(even):hover {
      background: #218838;
    }
    .navbar {
      background: rgba(255, 255, 255, 0.9);
      padding: 15px;
      border-bottom: 2px solid #ddd;
    }
  </style>
</head>
<body>

  <div class="container-fluid d-flex justify-content-center align-items-center bg-degradado-custom" style="min-height: 100vh;">
        <!-- Nav -->
        <nav class="navbar fixed-top bg-body-tertiary justify-content-center py-4">
            <div class="container-fluid d-flex justify-content-center align-items-center">
                <div class="d-flex align-items-center gap-2">
                
                <h2 class="fs-1" ><b>GLUCOTRACK</b></h2>
                    <img src="./imagenes_video/logoPrincipal.jpg" alt="Logo Principal" class="img-fluid rounded" style="width: 50px; height: auto;">
                </div>
            
               
            </div>
        </nav>


  <div class="menu-container">
  <div class="menu">
    <h3 class="text-dark mb-4 fs-2">Bienvenid@, <?= htmlspecialchars($username) ?> </h3>
    <div class="row g-3">
      <div class="col-6">
        <a href="controlGlucosa.php" class="btn btn-primary w-100 py-3 fw-bold">📊 Control Glucosa</a>
      </div>
      <div class="col-6">
        <a href="añadirRegistro.php" class="btn btn-primary w-100 py-3 fw-bold bg-success">🍽 Añadir Comida</a>
      </div>
      <div class="col-6">
        <a href="infoUsuario.php" class="btn btn-primary w-100 py-3 fw-bold bg-warning">📋 Modificar y eliminar</a>
      </div>
      <div class="col-6">
        <a href="estadisticas.php" class="btn btn-primary w-100 py-3 fw-bold bg-danger">📈 Estadísticas</a>
      </div>
    </div>
  </div>
</div>

</div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
