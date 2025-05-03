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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./estilosp.css">
</head>
<body>
    
</body>
</html>