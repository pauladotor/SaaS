<?php
include("../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $tipo_nomina = $_POST['tipo_nomina'];
    $estado = $_POST['estado'];
    $fecha_incorporacion = $_POST['fecha_incorporacion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $genero = $_POST['genero'];

    $sql = "INSERT INTO tb_personal (nombre, cedula, direccion, telefono, tipo_nomina, estado, fecha_incorporacion, fecha_nacimiento, genero)
            VALUES (:nombre, :cedula, :direccion, :telefono, :tipo_nomina, :estado, :fecha_incorporacion, :fecha_nacimiento, :genero)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nombre' => $nombre,
        'cedula' => $cedula,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'tipo_nomina' => $tipo_nomina,
        'estado' => $estado,
        'fecha_incorporacion' => $fecha_incorporacion,
        'fecha_nacimiento' => $fecha_nacimiento,
        'genero' => $genero
    ]);

    header("Location: ../view/personal.php?msg=ok");
    exit();
}
?>