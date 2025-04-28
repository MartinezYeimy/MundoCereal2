<?php
include 'Conexion_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos para la tabla producto
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $gramage = $_POST['gramage'];
    $precio_unitario = $_POST['precio_unitario'];
    $lote = $_POST['lote'];
    
    // Dato para la tabla inventario
    $cantidad = $_POST['cantidad'];

    // Insertar en producto
    $stmt = $conn->prepare("INSERT INTO producto (codigo, nombre, gramage, precio_unitario, lote) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $codigo, $nombre, $gramage, $precio_unitario, $lote);

    if ($stmt->execute()) {
        // Obtener el último ID insertado
        $id_producto = $conn->insert_id;

        // Insertar en inventario
        $stmt_inventario = $conn->prepare("INSERT INTO inventario (producto, gramage, cantidad, id_producto) VALUES (?, ?, ?, ?)");
        $stmt_inventario->bind_param("ssii", $nombre, $gramage, $cantidad, $id_producto);

        if ($stmt_inventario->execute()) {
            header("Location: inventario.php"); // Redireccionar al inventario
            exit();
        } else {
            echo "Error al insertar en inventario: " . $stmt_inventario->error;
        }
    } else {
        echo "Error al insertar en producto: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<header>Agregar Nuevo Producto</header>

<div class="container">
    <form action="agregar.php" method="post">
        <label>Código:</label>
        <input type="text" name="codigo" required>

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Gramaje:</label>
        <input type="text" name="gramage" required>

        <label>Precio Unitario:</label>
        <input type="number" step="0.01" name="precio_unitario" required>

        <label>Lote:</label>
        <input type="text" name="lote" required>

        <label>Cantidad en Inventario:</label>
        <input type="number" name="cantidad" required>

        <input type="submit" value="Agregar Producto" class="button add-button">
        <a href="inventario.php" class="button">Cancelar</a>
    </form>
</div>

</body>
</html>
