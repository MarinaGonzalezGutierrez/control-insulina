<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'dbdiabetes';

try{
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // Configurar el PDO para lanzar excepciones en caso de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die("Error de conexion :" .$e->getMessage());
}
?>