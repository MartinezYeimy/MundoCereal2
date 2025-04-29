<?php
include 'Conexion_db.php';

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos para la tabla producto
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $gramaje = $_POST['gramaje']; // ojo era 'gramaje' no 'gramage'
    $precio_unitario = $_POST['precio_unitario'];
    $lote = $_POST['lote'];
    
    // Datos para la tabla inventario
    $cantidad = $_POST['cantidad'];
    $precio_total = $cantidad * $precio_unitario;

    // Insertar en la tabla producto
    $stmt_producto = $conn->prepare("INSERT INTO producto (codigo, nombre, gramaje, precio_unitario, lote) VALUES (?, ?, ?, ?, ?)");
    $stmt_producto->bind_param("sssds", $codigo, $nombre, $gramaje, $precio_unitario, $lote);

    if ($stmt_producto->execute()) {
        // Obtener el último ID insertado en producto
        $id_producto = $conn->insert_id;

        // Insertar en inventario
        $stmt_inventario = $conn->prepare("INSERT INTO inventario (id_producto, cantidad, precio_unitario, precio_total) VALUES (?, ?, ?, ?)");
        $stmt_inventario->bind_param("iidd", $id_producto, $cantidad, $precio_unitario, $precio_total);

        if ($stmt_inventario->execute()) {
            header("Location: inventario.php"); // Redirigir al inventario
            exit();
        } else {
            echo "Error al insertar en inventario: " . $stmt_inventario->error;
        }
    } else {
        echo "Error al insertar en producto: " . $stmt_producto->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 0;
            text-align: center;
            font-size: 24px;
        }

        .container {
            max-width: 600px;
            background-color: white;
            padding: 30px;
            margin: 40px auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #2980b9;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>Agregar Producto</header>

<div class="container">
    <form method="post" action="">
        <label for="codigo">Código:</label>
        <input type="text" id="codigo" name="codigo" required>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="gramaje">Gramaje:</label>
        <input type="text" id="gramaje" name="gramaje" required>

        <label for="precio_unitario">Precio Unitario:</label>
        <input type="number" id="precio_unitario" name="precio_unitario" step="0.01" required>

        <label for="lote">Lote:</label>
        <input type="text" id="lote" name="lote" required>

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" required>

        <button type="submit">Agregar Producto</button>
    </form>

    <a href="inventario.php">Volver al Inventario</a>
</div>

</body>
</html>
