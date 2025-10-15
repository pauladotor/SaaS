<?php
// ../controller/guardar_cliente.php
include_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y limpiar datos del formulario
    $razon_social = isset($_POST['razon_social']) ? trim($_POST['razon_social']) : '';
    $nit = isset($_POST['nit']) ? trim($_POST['nit']) : '';
    $tipo_persona = isset($_POST['tipo_persona']) ? $_POST['tipo_persona'] : 'Normal';
    $representante = isset($_POST['representante']) ? trim($_POST['representante']) : '';
    $email_representante = isset($_POST['email_representante']) ? trim($_POST['email_representante']) : '';
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $ciudad = isset($_POST['ciudad']) ? trim($_POST['ciudad']) : '';
    $fecha_incorporacion = isset($_POST['fecha_incorporacion']) ? $_POST['fecha_incorporacion'] : date('Y-m-d');
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';

    try {
        // Insertar en la base de datos
        $sql = "INSERT INTO tb_clientes 
                (razon_social, nit, tipo_persona, representante, email_representante, direccion, telefono, ciudad, fecha_incorporacion, observaciones)
                VALUES (:razon_social, :nit, :tipo_persona, :representante, :email_representante, :direccion, :telefono, :ciudad, :fecha_incorporacion, :observaciones)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':razon_social' => $razon_social,
            ':nit' => $nit,
            ':tipo_persona' => $tipo_persona,
            ':representante' => $representante,
            ':email_representante' => $email_representante,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':ciudad' => $ciudad,
            ':fecha_incorporacion' => $fecha_incorporacion,
            ':observaciones' => $observaciones
        ]);

        // Redirigir con mensaje de éxito
        header("Location: ../view/clientes.php?msg=success");
        exit;

    } catch (PDOException $e) {
        // Redirigir con mensaje de error
        header("Location: ../view/clientes.php?msg=error&error=" . urlencode($e->getMessage()));
        exit;
    }

} else {
    // Si no es POST, redirigir
    header("Location: ../view/clientes.php");
    exit;
}
?>
