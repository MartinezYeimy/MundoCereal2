<?php
include '../dt_base/Conexion_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $gramaje = $_POST['gramaje'];
    $precio = $_POST['precio_unitario'];
    $lote = $_POST['lote'];

    $sql = "INSERT INTO producto (codigo, nombre, gramaje, precio_unitario, lote) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdis", $codigo, $nombre, $gramaje, $precio, $lote);
    $stmt->execute();

    header("Location: mostrar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eef2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #34495e;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            background-color: #27ae60;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #219150;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #2980b9;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Agregar Producto</h2>
    <form method="POST">
        <label for="codigo">Código:</label>
        <input type="text" name="codigo" id="codigo" required>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="gramaje">Gramaje:</label>
        <input type="text" name="gramaje" id="gramaje" required>

        <label for="precio_unitario">Precio Unitario:</label>
        <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" required>

        <label for="lote">Lote:</label>
        <input type="text" name="lote" id="lote" required>

        <input type="submit" value="Agregar">
    </form>

    <div class="back-link">
        <a href="mostrar.php">← Volver al listado</a>
    </div>
</div>

</body>
</html>
