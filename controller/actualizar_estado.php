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
    $estado = strtolower(trim($_POST['estado']));

    // Validar estado permitido
    if (!in_array($estado, ["activo", "desactivo"])) {
        echo json_encode(["success" => false, "message" => "Estado inválido"]);
        exit;
    }

    // Verificar que el registro exista
    $check = $pdo->prepare("SELECT id_personal FROM tb_personal WHERE id_personal = ?");
    $check->execute([$id]);

    if ($check->rowCount() === 0) {
        echo json_encode(["success" => false, "message" => "Registro no encontrado"]);
        exit;
    }

    // Actualizar estado
    $query = $pdo->prepare("UPDATE tb_personal SET estado = ? WHERE id_personal = ?");
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