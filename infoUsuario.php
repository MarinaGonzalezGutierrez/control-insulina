<?php
require 'database/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

// Obtener todos los registros de las tablas relacionadas
$query_registros = "
    SELECT 'controlglucosa' AS tabla, idUsuario, fecha, 'Control Glucosa' AS tipo, lenta, deporte, NULL AS glucosa_pre, NULL AS glucosa_post, NULL AS racion, NULL AS insulina, NULL AS correccion, NULL AS hora
    FROM controlglucosa WHERE idUsuario = :id_usuario
    UNION ALL
    SELECT 'comidas' AS tabla, idUsuario, fecha, 'Comida' AS tipo, NULL AS lenta, NULL AS deporte, glucosa_pre, glucosa_post, racion, insulina, NULL AS correccion, NULL AS hora
    FROM comidas WHERE idUsuario = :id_usuario
    UNION ALL
    SELECT 'hiper' AS tabla, idUsuario, fecha, 'Hiperglucemia' AS tipo, NULL AS lenta, NULL AS deporte, NULL AS glucosa_pre, NULL AS glucosa_post, NULL AS racion, NULL AS insulina, correccion, hora
    FROM hiper WHERE idUsuario = :id_usuario
    UNION ALL
    SELECT 'hipo' AS tabla, idUsuario, fecha, 'Hipoglucemia' AS tipo, NULL AS lenta, NULL AS deporte, NULL AS glucosa_pre, NULL AS glucosa_post, NULL AS racion, NULL AS insulina, NULL AS correccion, hora
    FROM hipo WHERE idUsuario = :id_usuario
";
$stmt_registros = $pdo->prepare($query_registros);
$stmt_registros->execute(['id_usuario' => $id_usuario]);
$registros = $stmt_registros->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Información del Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container-fluid bg-degradado-custom">
    <nav class="navbar fixed-top bg-body-tertiary py-3">
      <div class="container-fluid">
        <div class="d-flex align-items-center gap-2">
          <a href="dashboard.php" class="text-decoration-none text-dark d-flex align-items-center justify-content-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="me-2" width="24" height="24">
              <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span class="fs-5 me-5"><b>Home <<</b></span>
          </a>
        </div>
        <div class="d-flex justify-content-center align-items-center flex-grow-1">
          <h2 class="fs-1 mx-4"><b>GLUCOTRACK</b></h2>
          <img src="./imagenes_video/logoPrincipal.jpg" alt="Logo Principal" class="img-fluid rounded" style="width: 50px; height: auto;">
        </div>
      </div>
    </nav>
    
    <div class="container mt-5 pt-4">
        <h1 class="mb-4 text-center mt-4 text-light">Información del Usuario</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Lenta</th>
                    <th>Deporte</th>
                    <th>Glucosa Pre</th>
                    <th>Glucosa Post</th>
                    <th>Ración</th>
                    <th>Insulina</th>
                    <th>Corrección</th>
                    <th>Hora</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($registros)): ?>
                    <?php foreach ($registros as $registro): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($registro['fecha'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($registro['tipo'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($registro['lenta'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($registro['deporte'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($registro['glucosa_pre'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($registro['glucosa_post'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($registro['racion'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($registro['insulina'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($registro['correccion'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($registro['hora'] ?? ''); ?></td>
                            <td>
                                <a href="modificarRegistro.php?tabla=<?php echo htmlspecialchars($registro['tabla'] ?? ''); ?>&fecha=<?php echo htmlspecialchars($registro['fecha'] ?? ''); ?>" class="btn btn-warning btn-sm">✏️</a>
                                <a href="borrarRegistro.php?tabla=<?php echo htmlspecialchars($registro['tabla'] ?? ''); ?>&fecha=<?php echo htmlspecialchars($registro['fecha'] ?? ''); ?>" class="btn btn-danger btn-sm">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center">No hay registros disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
