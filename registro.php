<?php
//conexion a la base de datos
require 'database/conexion.php';
//iniciar sesion
session_start();

//Validacion Form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $apellido = $_POST["apellido"] ?? '';
    $fechaNac = $_POST["fechaNac"] ?? '';
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';
    
    if(empty($nombre) || empty($apellido) || empty($fechaNac) || empty($password)){
        echo "Todos los campos son obligatorios";
        exit();
    }

    // Validar que la fecha de nacimiento no sea futura
    $fechaActual = date("Y-m-d");
    if ($fechaNac > $fechaActual) {
        echo "Error: la fecha de nacimiento no puede ser futura.";
        exit();
    }

    //Verificar si el nombre de usuario ya existe
    $checkUser = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE username = ?");
    $checkUser->execute([$username]);

    if ($checkUser->fetchColumn() > 0){
        echo "Error: el nombre de usuario ya está en uso.";
        exit();
    }

    //Hashear la contraseña de forma segura
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    //Insertar Usuario en la base de datos
    $sql = "INSERT INTO usuario (nombre, apellido, fechaNac, username, password) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nombre, $apellido, $fechaNac, $username, $hashedPassword])) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error al registrar.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home página diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/estilos.css?v=1.0">
    
</head>
<body >
    <div class="container-fluid bg-degradado-custom d-flex justify-content-center align-items-center" style="min-height: 100vh; padding-top: 80px;">
        <!-- Nav -->
        <nav class="navbar fixed-top bg-body-tertiary justify-content-center py-4">
            <div class="container-fluid d-flex justify-content-center align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <a class="navbar-brand text-center" href="index.php">
                    <h2 class="fs-1" ><b>GLUCOTRACK</b></h2>
                    </a>
                    <img src="./imagenes_video/logoPrincipal.jpg" alt="Logo Principal" class="img-fluid rounded" style="width: 50px; height: auto;">
                </div>
            </div>
        </nav>

        <!-- Contenido Principal-->
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 bg-white p-4 rounded shadow-lg">
            <h3 class="text-center text-dark mb-2">Regístrate</h3>
            <form action="" method="POST">

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre" required>
                </div>

                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese su apellido" required>
                </div>

                <div class="mb-3">
                    <label for="fechaNac" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fechaNac" name="fechaNac" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Ingrese su nombre de usuario" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" required>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>

                <div class="text-center mt-3">
                    <p>¿Ya tienes una cuenta? <a href="index.php">Iniciar sesión</a></p>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
