<?php
ob_start(); // Inicia el búfer de salida
session_start();
require 'database/conexion.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Por favor, completa todos los campos.';
    } else {
        // Consulta preparada para evitar inyección SQL
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['idUsuario'];
            $_SESSION['usuario_username'] = $usuario['username'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Usuario o Contraseña incorrecta";
        }
    }
}
ob_end_flush(); // Envía el contenido del búfer y lo limpia
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login página diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
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
        <!-- Contenido principal -->
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 bg-white p-4 rounded shadow-lg">
            <h3 class="text-center text-dark mb-3">Iniciar sesión</h3>
            <?php if ($error): ?>
                <p class="text-danger text-center"> <?= htmlspecialchars($error) ?> </p>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label text-dark">Nombre de Usuario</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Ingrese su usuario" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-dark">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" required>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
                <div class="text-center mt-3">
                    <p class="text-dark">¿No tienes una cuenta? <a href="registro.php" class="text-dark">Regístrate</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
