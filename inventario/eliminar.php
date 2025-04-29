<?php
include '../dt_base/Conexion_db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Asegura que $id sea un número entero

    if ($id > 0) { // Solo si el ID es válido (mayor a 0)
        $sql = "DELETE FROM inventario WHERE id_Inventario = $id";

        if ($conn->query($sql) === TRUE) {
            header("Location: inventario.php");
            exit(); // Importante: detener el script después de redirigir
        } else {
            echo "Error eliminando: " . $conn->error;
        }
    } else {
        echo "ID inválido.";
    }
} else {
    echo "ID no especificado.";
}
?>
