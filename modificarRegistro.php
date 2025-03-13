<?php
require 'database/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$tabla = $_GET['tabla'];
$fecha = $_GET['fecha'];
$tipoComida = $_GET['tipoComida'] ?? null;

// Obtener los datos del registro
if ($tipoComida) {
    $query = "SELECT * FROM $tabla WHERE idUsuario = :id_usuario AND fecha = :fecha AND tipoComida = :tipoComida";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id_usuario' => $id_usuario, 'fecha' => $fecha, 'tipoComida' => $tipoComida]);
} else {
    $query = "SELECT * FROM $tabla WHERE idUsuario = :id_usuario AND fecha = :fecha";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id_usuario' => $id_usuario, 'fecha' => $fecha]);
}
$registro = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Actualizar los datos del registro
    $fecha = $_POST['fecha'];
    $glucosa_pre = $_POST['glucosa_pre'] ?? null;
    $glucosa_post = $_POST['glucosa_post'] ?? null;
    $racion = $_POST['racion'] ?? null;
    $insulina = $_POST['insulina'] ?? null;
    $correccion = $_POST['correccion'] ?? null;
    $hora = $_POST['hora'] ?? null;
    $lenta = $_POST['lenta'] ?? null;
    $deporte = $_POST['deporte'] ?? null;

    $updateFields = [];
    $updateParams = ['fecha' => $fecha, 'id_usuario' => $id_usuario];

    if ($glucosa_pre !== null) {
        $updateFields[] = 'glucosa_pre = :glucosa_pre';
        $updateParams['glucosa_pre'] = $glucosa_pre;
    }
    if ($glucosa_post !== null) {
        $updateFields[] = 'glucosa_post = :glucosa_post';
        $updateParams['glucosa_post'] = $glucosa_post;
    }
    if ($racion !== null) {
        $updateFields[] = 'racion = :racion';
        $updateParams['racion'] = $racion;
    }
    if ($insulina !== null) {
        $updateFields[] = 'insulina = :insulina';
        $updateParams['insulina'] = $insulina;
    }
    if ($correccion !== null) {
        $updateFields[] = 'correccion = :correccion';
        $updateParams['correccion'] = $correccion;
    }
    if ($hora !== null) {
        $updateFields[] = 'hora = :hora';
        $updateParams['hora'] = $hora;
    }
    if ($lenta !== null) {
        $updateFields[] = 'lenta = :lenta';
        $updateParams['lenta'] = $lenta;
    }
    if ($deporte !== null) {
        $updateFields[] = 'deporte = :deporte';
        $updateParams['deporte'] = $deporte;
    }

    $updateFieldsString = implode(', ', $updateFields);

    if ($tipoComida) {
        $query = "UPDATE $tabla SET $updateFieldsString WHERE idUsuario = :id_usuario AND fecha = :fecha AND tipoComida = :tipoComida";
        $updateParams['tipoComida'] = $tipoComida;
    } else {
        $query = "UPDATE $tabla SET $updateFieldsString WHERE idUsuario = :id_usuario AND fecha = :fecha";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($updateParams);

    header('Location: infoUsuario.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="container-fluid d-flex justify-content-center align-items-center bg-degradado-custom" style="min-height: 100vh;">
<nav class="navbar fixed-top bg-body-tertiary py-4">
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

    <div class="container mt-5 bg-light rounded p-4">
        <h1 class="mb-4">Editar Registro</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo htmlspecialchars($registro['fecha']); ?>" required>
            </div>

            <?php if (array_key_exists('glucosa_pre', $registro)): ?>
            <div class="mb-3">
                <label for="glucosa_pre" class="form-label">Glucosa Pre (mg/dL)</label>
                <input type="number" class="form-control" id="glucosa_pre" name="glucosa_pre" value="<?php echo htmlspecialchars($registro['glucosa_pre']); ?>" min="70" max="130">
                <small class="form-text text-muted">Rango recomendado: 70 - 130 mg/dL</small>
            </div>
            <?php endif; ?>

            <?php if (array_key_exists('glucosa_post', $registro)): ?>
            <div class="mb-3">
                <label for="glucosa_post" class="form-label">Glucosa Post (mg/dL)</label>
                <input type="number" class="form-control" id="glucosa_post" name="glucosa_post" value="<?php echo htmlspecialchars($registro['glucosa_post']); ?>" min="100" max="180">
                <small class="form-text text-muted">Rango recomendado: 100 - 180 mg/dL</small>
            </div>
            <?php endif; ?>

            <?php if (array_key_exists('racion', $registro)): ?>
            <div class="mb-3">
                <label for="racion" class="form-label">Ración</label>
                <input type="number" class="form-control" id="racion" name="racion" value="<?php echo htmlspecialchars($registro['racion']); ?>">
            </div>
            <?php endif; ?>

            <?php if (array_key_exists('insulina', $registro)): ?>
            <div class="mb-3">
                <label for="insulina" class="form-label">Insulina (U/mL)</label>
                <input type="number" class="form-control" id="insulina" name="insulina" value="<?php echo htmlspecialchars($registro['insulina']); ?>" min="0" max="100">
                <small class="form-text text-muted">Rango recomendado: 0 - 100 U/mL</small>
            </div>
            <?php endif; ?>

            <?php if (array_key_exists('correccion', $registro)): ?>
            <div class="mb-3">
                <label for="correccion" class="form-label">Corrección</label>
                <input type="number" class="form-control" id="correccion" name="correccion" value="<?php echo htmlspecialchars($registro['correccion']); ?>">
            </div>
            <?php endif; ?>

            <?php if (array_key_exists('hora', $registro)): ?>
            <div class="mb-3">
                <label for="hora" class="form-label">Hora</label>
                <input type="time" class="form-control" id="hora" name="hora" value="<?php echo htmlspecialchars($registro['hora']); ?>">
            </div>
            <?php endif; ?>

            <?php if (array_key_exists('lenta', $registro)): ?>
            <div class="mb-3">
                <label for="lenta" class="form-label">Lenta</label>
                <input type="number" class="form-control" id="lenta" name="lenta" value="<?php echo htmlspecialchars($registro['lenta']); ?>">
            </div>
            <?php endif; ?>

            <?php if (array_key_exists('deporte', $registro)): ?>
            <div class="mb-3">
                <label for="deporte" class="form-label">Deporte</label>
                <input type="number" class="form-control" id="deporte" name="deporte" value="<?php echo htmlspecialchars($registro['deporte']); ?>">
            </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
