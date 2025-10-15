<?php
require_once("../config/conexion.php");

header("Content-Type: application/json; charset=utf-8");

try {
    // Validar que lleguen los datos
    if (!isset($_POST['id']) || !isset($_POST['estado'])) {
        echo json_encode(["success" => false, "message" => "Datos incompletos"]);
        exit;
    }

    $id = intval($_POST['id']);
    $estado = trim($_POST['estado']);

    // Validar estado permitido
    if (!in_array($estado, ["Activo", "Inactivo"])) {
        echo json_encode(["success" => false, "message" => "Estado inválido"]);
        exit;
    }

    // Verificar que el registro exista
    $check = $pdo->prepare("SELECT id FROM tb_clientes WHERE id = ?");
    $check->execute([$id]);

    if ($check->rowCount() === 0) {
        echo json_encode(["success" => false, "message" => "Cliente no encontrado"]);
        exit;
    }

    // Actualizar estado
    $query = $pdo->prepare("UPDATE tb_clientes SET estado = ? WHERE id = ?");
    $resultado = $query->execute([$estado, $id]);

    if ($resultado) {
        echo json_encode(["success" => true, "message" => "Estado actualizado correctamente"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar estado"]);
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error en base de datos: " . $e->getMessage()]);
}
?>
