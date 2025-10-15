<?php
include("../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $tipo_documento = isset($_POST['tipo_documento']) ? $_POST['tipo_documento'] : '';
    $numero_documento = isset($_POST['numero_documento']) ? trim($_POST['numero_documento']) : '';
    $fecha_incorporacion = isset($_POST['fecha_incorporacion']) ? $_POST['fecha_incorporacion'] : date('Y-m-d');
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';
    $estado = isset($_POST['estado']) ? $_POST['estado'] : 'Activo';

    try {
        $sql = "INSERT INTO tb_proveedores (nombre, tipo_documento, numero_documento, fecha_incorporacion, direccion, telefono, email, observaciones, estado) 
                VALUES (:nombre, :tipo_documento, :numero_documento, :fecha_incorporacion, :direccion, :telefono, :email, :observaciones, :estado)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':tipo_documento' => $tipo_documento,
            ':numero_documento' => $numero_documento,
            ':fecha_incorporacion' => $fecha_incorporacion,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':email' => $email,
            ':observaciones' => $observaciones,
            ':estado' => $estado
        ]);

        header("Location: ../view/proveedores.php?msg=success");
        exit;

    } catch (PDOException $e) {
        header("Location: ../view/proveedores.php?msg=error&error=".urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: ../view/proveedores.php");
    exit;
}
?>
