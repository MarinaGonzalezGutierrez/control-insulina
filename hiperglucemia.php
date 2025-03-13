<?php
require 'database/conexion.php';
session_start();

$message = ''; // Mensaje de éxito o error
$alertClass = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idUsuario = $_SESSION['usuario_id'];
    $fechaHiper = $_POST["fechaHiper"] ?? null;
    $tipoComidaHiper = $_POST["tipoComidaHiper"] ?? null;
    $horaHiper = $_POST["horaHiper"] ?? null;
    $glucosaHiper = $_POST["glucosaHiper"] ?? null;
    $correcHiper = $_POST["correcHiper"] ?? null;

    if (!isset($fechaHiper) || !isset($tipoComidaHiper) || !isset($horaHiper) || !isset($glucosaHiper) || !isset($correcHiper)) {
        $message = 'Por favor, complete todos los campos.';
        $alertClass = 'alert-danger'; // Alerta de error
    } else {
        // Verificar si ya existe un registro en comidas para la combinación de idUsuario, fecha y tipoComida
        $sqlCheckComidas = "SELECT COUNT(*) FROM comidas WHERE idUsuario = ? AND fecha = ? AND tipoComida = ?";
        $stmtCheckComidas = $pdo->prepare($sqlCheckComidas);
        $stmtCheckComidas->execute([$idUsuario, $fechaHiper, $tipoComidaHiper]);
        $countComidas = $stmtCheckComidas->fetchColumn();

        if ($countComidas == 0) {
            // Insertar un nuevo registro en comidas si no existe
            $sqlInsertComidas = "INSERT INTO comidas (idUsuario, fecha, tipoComida) VALUES (?, ?, ?)";
            $stmtInsertComidas = $pdo->prepare($sqlInsertComidas);
            $stmtInsertComidas->execute([$idUsuario, $fechaHiper, $tipoComidaHiper]);
        }

        // Insertar el nuevo registro en hiper
        $sql = "INSERT INTO hiper (idUsuario, fecha, tipoComida, hora, glucosa, correccion) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$idUsuario, $fechaHiper, $tipoComidaHiper, $horaHiper, $glucosaHiper, $correcHiper])) {
            $message = 'Registro exitoso.';
            $alertClass = 'alert-success'; // Alerta de éxito
        } else {
            $message = 'Error al registrar.';
            $alertClass = 'alert-danger'; // Alerta de error
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
                    <a href="añadirRegistro.php" class="text-decoration-none text-dark d-flex align-items-center justify-content-center">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="me-2" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg> -->

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>

                        <span class="fs-5 me-5 ms-2"><b>Añadir Comida</b></span>
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
                <h3 class="text-center mb-3">Hiperglucemia</h3>

                <div class="row mb-3">
                    <label for="fechaHiper" class="col-form-label">Fecha</label>
                    <input type="date" class="form-control" id="fechaHiper" name="fechaHiper" required>
                </div>

                <div class="row mb-3">
                    <label for="tipoComidaHiper" class="col-form-label">Tipo de comida</label>
                    <select class="form-select" id="tipoComidaHiper" name="tipoComidaHiper">
                        <option selected disabled>Elige...</option>
                        <option value="Desayuno">Desayuno</option>
                        <option value="Aperitivo">Aperitivo</option>
                        <option value="Comida">Comida</option>
                        <option value="Merienda">Merienda</option>
                        <option value="Cena">Cena</option>
                    </select>
                </div>

                <div class="row mb-3">
                    <label for="horaHiper" class="col-form-label">Hora</label>
                    <input type="time" class="form-control" id="horaHiper" name="horaHiper" required>
                </div>

                <div class="row mb-3">
                    <label for="glucosaHiper" class="col-form-label">Glucosa</label>
                    <input type="number" class="form-control" id="glucosaHiper" name="glucosaHiper" min="180" max="300" required>
                    <small class="form-text text-muted">Rango recomendado: 180 - 300 mg/dL</small>
                </div>

                <div class="row mb-3">
                    <label for="correcHiper" class="col-form-label">Correccion</label>
                    <input type="number" class="form-control" id="correcHiper" name="correcHiper" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Insertar</button>
            </form>
        </div>
    </div>
</body>

</html>