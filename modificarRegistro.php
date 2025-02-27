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
    <title>Editar Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Editar Registro</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo htmlspecialchars($registro['fecha']); ?>" required>
            </div>
            <?php if (array_key_exists('glucosa_pre', $registro)): ?>
            <div class="mb-3">
                <label for="glucosa_pre" class="form-label">Glucosa Pre</label>
                <input type="number" class="form-control" id="glucosa_pre" name="glucosa_pre" value="<?php echo htmlspecialchars($registro['glucosa_pre']); ?>">
            </div>
            <?php endif; ?>
            <?php if (array_key_exists('glucosa_post', $registro)): ?>
            <div class="mb-3">
                <label for="glucosa_post" class="form-label">Glucosa Post</label>
                <input type="number" class="form-control" id="glucosa_post" name="glucosa_post" value="<?php echo htmlspecialchars($registro['glucosa_post']); ?>">
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
                <label for="insulina" class="form-label">Insulina</label>
                <input type="number" class="form-control" id="insulina" name="insulina" value="<?php echo htmlspecialchars($registro['insulina']); ?>">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>