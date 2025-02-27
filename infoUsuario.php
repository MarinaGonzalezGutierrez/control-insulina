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
    <div class="container mt-5">
        <h1 class="mb-4">Información del Usuario</h1>

        <!-- Mostrar todos los registros en una única tabla -->
        <h2>Registros</h2>
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
                            <td><?php echo htmlspecialchars($registro['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($registro['tipo']); ?></td>
                            <td><?php echo htmlspecialchars($registro['lenta']); ?></td>
                            <td><?php echo htmlspecialchars($registro['deporte']); ?></td>
                            <td><?php echo htmlspecialchars($registro['glucosa_pre']); ?></td>
                            <td><?php echo htmlspecialchars($registro['glucosa_post']); ?></td>
                            <td><?php echo htmlspecialchars($registro['racion']); ?></td>
                            <td><?php echo htmlspecialchars($registro['insulina']); ?></td>
                            <td><?php echo htmlspecialchars($registro['correccion']); ?></td>
                            <td><?php echo htmlspecialchars($registro['hora']); ?></td>
                            <td>
                                <a href="modificarRegistro.php?tabla=<?php echo htmlspecialchars($registro['tabla']); ?>&fecha=<?php echo htmlspecialchars($registro['fecha']); ?>&tipoComida=<?php echo htmlspecialchars($registro['tipoComida'] ?? ''); ?>" class="btn btn-warning btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.146.854a.5.5 0 0 1 .708 0l2.292 2.292a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-4 1a.5.5 0 0 1-.65-.65l1-4a.5.5 0 0 1 .11-.168l10-10zM11.207 2L3 10.207V11h.793L13 3.793 11.207 2zM2 12v1h1l8-8-1-1-8 8z"/>
                                    </svg>
                                </a>
                                <a href="borrarRegistro.php?tabla=<?php echo htmlspecialchars($registro['tabla']); ?>&fecha=<?php echo htmlspecialchars($registro['fecha']); ?>&tipoComida=<?php echo htmlspecialchars($registro['tipoComida'] ?? ''); ?>" class="btn btn-danger btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 5h4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5H6a.5.5 0 0 1-.5-.5v-7zM4.118 4a1 1 0 0 1 .876-.5h6.012a1 1 0 0 1 .876.5H14.5a.5.5 0 0 1 0 1h-1v9a2 2 0 0 1-2 2H4.5a2 2 0 0 1-2-2V5h-1a.5.5 0 0 1 0-1h1.618zM6.5 1a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1h-3z"/>
                                    </svg>
                                </a>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>