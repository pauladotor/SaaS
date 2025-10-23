<?php
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $costo_indirecto = $_POST['costo_indirecto'];
    $costo_financiero = $_POST['costo_financiero'];
    $costo_distribucion = $_POST['costo_distribucion'];
    $materiales = $_POST['materiales'] ?? [];

    try {
        $pdo->beginTransaction();

        // Insertar categoría
        $stmt = $pdo->prepare("INSERT INTO categorias (nombre, costo_indirecto, costo_financiero, costo_distribucion)
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $costo_indirecto, $costo_financiero, $costo_distribucion]);
        $categoria_id = $pdo->lastInsertId();

        // Insertar materiales relacionados
        foreach ($materiales as $id => $mat) {
            if (isset($mat['seleccionado']) && $mat['seleccionado'] == 1) {
                $cantidad = !empty($mat['cantidad']) ? $mat['cantidad'] : 0;
                $stmtMat = $pdo->prepare("INSERT INTO categoria_materiales (categoria_id, material_id, cantidad)
                                          VALUES (?, ?, ?)");
                $stmtMat->execute([$categoria_id, $id, $cantidad]);
            }
        }

        $pdo->commit();
        header("Location: articulos.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
