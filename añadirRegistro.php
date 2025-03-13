<?php
require 'database/conexion.php';
session_start();

$message = ''; // Mensaje de éxito o error
$alertClass = ''; // Clase de Bootstrap para el estilo de la alerta

// Registro de comida
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['glucosa_pre'])) {
  $idUsuario = $_SESSION['usuario_id'];
  $fecha = date('Y-m-d'); // Fecha de hoy
  $glucosa_pre = $_POST["glucosa_pre"] ?? null;
  $glucosa_post = $_POST["glucosa_post"] ?? null;
  $racion = $_POST["racion"] ?? null;
  $insulina = $_POST["insulina"] ?? null;
  $tipoComida = $_POST["tipoComida"] ?? null;

  // Validar que no sean nulos
  if (!isset($glucosa_pre) || !isset($glucosa_post) || !isset($racion) || !isset($insulina) || !isset($tipoComida)) {
    $message = 'Por favor, complete todos los campos.';
    $alertClass = 'alert-danger'; // Alerta de error
  } else {
    // Verificar si ya existe un registro en controlglucosa para el mismo día
    $sqlCheckControl = "SELECT COUNT(*) FROM controlglucosa WHERE idUsuario = ? AND fecha = ?";
    $stmtCheckControl = $pdo->prepare($sqlCheckControl);
    $stmtCheckControl->execute([$idUsuario, $fecha]);
    $countControl = $stmtCheckControl->fetchColumn();

    if ($countControl == 0) {
      // Insertar un nuevo registro en controlglucosa si no existe
      $sqlInsertControl = "INSERT INTO controlglucosa (idUsuario, fecha) VALUES (?, ?)";
      $stmtInsertControl = $pdo->prepare($sqlInsertControl);
      $stmtInsertControl->execute([$idUsuario, $fecha]);
    }

    // Verificar si ya existe un registro para el mismo día y tipo de comida
    $sqlCheck = "SELECT COUNT(*) FROM comidas WHERE idUsuario = ? AND fecha = ? AND tipoComida = ?";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$idUsuario, $fecha, $tipoComida]);
    $count = $stmtCheck->fetchColumn();

    if ($count > 0) {
      // Si ya existe un registro de esa comida en el día, mostrar un mensaje de error
      $message = 'Ya existe un registro para este tipo de comida en el día de hoy.';
      $alertClass = 'alert-danger'; // Alerta de error
    } else {
      // Insertar el nuevo registro
      $sql = "INSERT INTO comidas (idUsuario, fecha, glucosa_pre, glucosa_post, racion, insulina, tipoComida) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt = $pdo->prepare($sql);

      if ($stmt->execute([$idUsuario, $fecha, $glucosa_pre, $glucosa_post, $racion, $insulina, $tipoComida])) {
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
  <title>Añadir Registro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
  <div class="container-fluid d-flex justify-content-center align-items-center bg-degradado-custom" style="min-height: 100vh;">
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

    <!-- Contenido principal -->
    <div class="col-12 col-sm-8 col-md-6 col-lg-4 bg-white p-4 rounded shadow-lg">
      <!-- AÑADIR COMIDA -->
      <form method="POST">
        <h3 class="text-center mb-3">Añadir Registro Comida</h3>

        <div class="row mb-3">
          <label for="glucosa_pre" class="col-form-label">Glucosa 1H Antes (mg/dL)</label>
          <input type="number" class="form-control" id="glucosa_pre" name="glucosa_pre" min="70" max="130" required>
          <small class="form-text text-muted">Rango recomendado: 70 - 130 mg/dL</small>
        </div>

        <div class="row mb-3">
          <label for="glucosa_post" class="col-form-label">Glucosa 2H Después (mg/dL)</label>
          <input type="number" class="form-control" id="glucosa_post" name="glucosa_post" min="100" max="180" required>
          <small class="form-text text-muted">Rango recomendado: 100 - 180 mg/dL</small>
        </div>

        <div class="row mb-3">
          <label for="racion" class="col-form-label">Ración</label>
          <input type="number" class="form-control" id="racion" name="racion" required>
        </div>

        <div class="row mb-3">
          <label for="insulina" class="col-form-label">Insulina (U/mL)</label>
          <input type="number" class="form-control" id="insulina" name="insulina" min="0" max="100" required>
          <small class="form-text text-muted">Rango recomendado: 0 - 100 U/mL</small>
        </div>

        <div class="row mb-3">
          <label for="tipoComida" class="col-form-label">Tipo de comida</label>
          <select class="form-select" id="tipoComida" name="tipoComida">
            <option selected disabled>Elige...</option>
            <option value="Desayuno">Desayuno</option>
            <option value="Aperitivo">Aperitivo</option>
            <option value="Comida">Comida</option>
            <option value="Merienda">Merienda</option>
            <option value="Cena">Cena</option>
          </select>
        </div>
     
        <button type="submit" class="btn btn-primary w-100">Insertar</button>

        <div class="d-flex justify-content-center mt-3 gap-3">
          <a href="hiperglucemia.php" class="btn btn-success w-48">Hiperglucemia</a>
          <a href="hipoglucemia.php" class="btn btn-info w-48">Hipoglucemia</a>
        </div>

      </form>
    </div>
  </div>
</body>

</html>
