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

try {
    $pdo->beginTransaction();

    // Eliminar registros relacionados en las tablas dependientes
    if ($tabla == 'controlglucosa') {
        $query = "DELETE FROM comidas WHERE idUsuario = :id_usuario AND fecha = :fecha";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_usuario' => $id_usuario, 'fecha' => $fecha]);

        $query = "DELETE FROM hiper WHERE idUsuario = :id_usuario AND fecha = :fecha";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_usuario' => $id_usuario, 'fecha' => $fecha]);

        $query = "DELETE FROM hipo WHERE idUsuario = :id_usuario AND fecha = :fecha";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_usuario' => $id_usuario, 'fecha' => $fecha]);
    } elseif ($tabla == 'comidas') {
        $query = "DELETE FROM hiper WHERE idUsuario = :id_usuario AND fecha = :fecha AND tipoComida = :tipoComida";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_usuario' => $id_usuario, 'fecha' => $fecha, 'tipoComida' => $tipoComida]);

        $query = "DELETE FROM hipo WHERE idUsuario = :id_usuario AND fecha = :fecha AND tipoComida = :tipoComida";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_usuario' => $id_usuario, 'fecha' => $fecha, 'tipoComida' => $tipoComida]);
    }

    // Eliminar el registro principal
    if ($tipoComida) {
        $query = "DELETE FROM $tabla WHERE idUsuario = :id_usuario AND fecha = :fecha AND tipoComida = :tipoComida";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_usuario' => $id_usuario, 'fecha' => $fecha, 'tipoComida' => $tipoComida]);
    } else {
        $query = "DELETE FROM $tabla WHERE idUsuario = :id_usuario AND fecha = :fecha";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_usuario' => $id_usuario, 'fecha' => $fecha]);
    }

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Error: " . $e->getMessage());
}

header('Location: infoUsuario.php');
exit();
?>