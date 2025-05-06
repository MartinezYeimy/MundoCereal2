<?php
include '../dt_base/Conexion_db.php'; // Importa la conexión
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "
        UPDATE inventario i
        JOIN producto p ON i.id_producto = p.id_Producto
        SET 
            i.producto = p.nombre,
            i.gramaje = p.gramaje,
            i.precio_unitario = p.precio_unitario
    ";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "✅ Inventario actualizado correctamente.";
    } else {
        $mensaje = "❌ Error al actualizar: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Actualizar Inventario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f3;
            text-align: center;
            padding-top: 100px;
        }
        .container {
            background-color: #fff;
            display: inline-block;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .mensaje {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Actualizar Inventario desde Producto</h2>
    <form method="post">
        <button class="btn" type="submit">Actualizar</button>
    </form>
    <?php if ($mensaje): ?>
        <div class="mensaje"><?= $mensaje ?></div>
    <?php endif; ?>
</div>
</body>
</html>
