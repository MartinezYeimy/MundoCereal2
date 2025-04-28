<?php
include 'Conexion_db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM inventario WHERE id_inventario=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: inventario.php");
    } else {
        echo "Error eliminando: " . $conn->error;
    }
} else {
    echo "ID no especificado.";
}
?>


