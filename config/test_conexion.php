<?php
require_once "conexion.php";

try {
    $stmt = $pdo->query("SHOW DATABASES;");
    echo "<h3>✅ Conexión exitosa. Bases de datos encontradas:</h3>";
    echo "<ul>";
    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . htmlspecialchars($fila['Database']) . "</li>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    echo "❌ Error al ejecutar la consulta: " . $e->getMessage();
}
?>
