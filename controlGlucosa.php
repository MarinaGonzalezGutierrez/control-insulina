<?php
require 'database/conexion.php';
session_start();

$message = ''; // Mensaje de éxito o error
$alertClass = ''; // Clase de Bootstrap para el estilo de la alerta

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $idUsuario = $_SESSION['usuario_id'];
    $lenta = $_POST["lenta"] ?? null;
    $deporte = $_POST["deporte"] ?? null;
    $fecha = date('Y-m-d'); // Corrección del formato de fecha (solo DATE)

    // Validar que no sean nulos (permitiendo 0 como valor válido)
    if (!isset($lenta) || !isset($deporte)) {
        $message = 'Por favor, complete todos los campos.';
        $alertClass = 'alert-danger'; // Alerta de error
    } else {
        // Verificar si ya existe registro el día de hoy
        $sqlCheck = "SELECT COUNT(*) FROM controlglucosa WHERE idUsuario = ? AND fecha = ?";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([$idUsuario, $fecha]);
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            $message = 'No se pueden hacer más registros el día de hoy.';
            $alertClass = 'alert-danger'; // Alerta de error
        } else {
            // Insertar el nuevo registro
            $sql = "INSERT INTO controlglucosa (idUsuario, fecha, lenta, deporte) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$idUsuario, $fecha, $lenta, $deporte])) {
                $message = 'Registro exitoso.';
                $alertClass = 'alert-success'; // Alerta de éxito
            } else {
                $message = 'Error al registrar.';
                $alertClass = 'alert-danger'; // Alerta de error
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Control Glucosa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
  <!-- Contenedor principal -->
<div class="container-fluid d-flex flex-column justify-content-center align-items-center bg-degradado-custom" style="min-height: 100vh;">
  
  <!-- Nav -->
  <nav class="navbar fixed-top bg-body-tertiary py-4">
      <div class="container-fluid">
        <!-- Bloque para el enlace Home alineado a la izquierda -->
        <div class="d-flex align-items-center gap-2">
          <a href="dashboard.php" class="text-decoration-none text-dark d-flex align-items-center justify-content-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="me-2" width="24" height="24">
              <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span class="fs-5 me-5"><b>Home <<</b></span>
          </a>
        </div>
        
        <!-- Bloque para el título GlucoTrack centrado -->
        <div class="d-flex justify-content-center align-items-center flex-grow-1">
          <h2 class="fs-1 mx-4"><b>GLUCOTRACK</b></h2>
          <img src="./imagenes_video/logoPrincipal.jpg" alt="Logo Principal" class="img-fluid rounded" style="width: 50px; height: auto;">
        </div>
      </div>
    </nav>

  <!-- Contenido principal (Formulario) -->
  <div class="col-12 col-sm-8 col-md-6 col-lg-4 bg-white p-4 rounded shadow-lg mt-3">
    <form method="POST">
      <h3 class="text-center mb-3">Control de Glucosa</h3>

      <?php if ($message): ?>
        <div class="alert <?= $alertClass; ?> alert-dismissible fade show" role="alert">
          <?= $message; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <div class="row mb-3">
        <label for="lenta" class="col-sm-2 col-form-label">Lenta</label>
        <div class="col-sm-10">
          <input type="number" class="form-control" id="lenta" name="lenta" required>
        </div>
      </div>

      <div class="row mb-3">
        <label for="deporte" class="col-sm-2 col-form-label">Deporte</label>
        <div class="col-sm-8">
          <input type="range" class="form-range" min="0" max="5" id="deporte" name="deporte" value="0" oninput="actualizarValor(this.value)">
        </div>
        <div class="col-sm-2">
          <span id="valorDeporte">0</span>
        </div>
      </div>

      <script>
        function actualizarValor(valor) {
          document.getElementById("valorDeporte").textContent = valor;
        }
      </script>

      <button type="submit" class="btn btn-primary justify-content-center mx-auto d-flex">Insertar</button>
    </form>
  </div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>