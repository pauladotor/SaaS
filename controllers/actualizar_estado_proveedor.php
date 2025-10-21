<?php
header('Content-Type: application/json');
include("../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';

    if ($id > 0 && in_array($estado, ['Activo','Inactivo'])) {
        try {
            $stmt = $pdo->prepare("UPDATE tb_proveedores SET estado = :estado WHERE id = :id");
            $stmt->execute(['estado' => $estado, 'id' => $id]);
            echo json_encode(['primary' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => "Datos inválidos. ID: $id, Estado: $estado"]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
