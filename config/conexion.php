<?php
$host = "127.0.0.1"; 
$usuario = "root";   
$contrasena = "313878"; 
$base_datos = "Albania_SaaS";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$base_datos;charset=utf8", $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa a la base de datos";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
