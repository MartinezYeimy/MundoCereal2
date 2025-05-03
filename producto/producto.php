<?php
include '../dt_base/Conexion_db.php';

$sql = "SELECT * FROM producto";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .add-button {
            display: inline-block;
            padding: 10px 16px;
            margin-bottom: 20px;
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .add-button:hover {
            background-color: #1e8f50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #3498db;
            color: white;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .action-btn {
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 14px;
            margin: 0 3px;
        }

        .edit-btn {
            background-color: #f39c12;
            color: white;
        }

        .edit-btn:hover {
            background-color: #e67e22;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Lista de Productos</h2>
    <a class="add-button" href="agregar.php">+ Agregar Producto</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Gramaje</th>
                <th>Precio Unitario</th>
                <th>Lote</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id_Producto']) ?></td>
                <td><?= htmlspecialchars($row['codigo']) ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['gramaje']) ?></td>
                <td><?= htmlspecialchars($row['precio_unitario']) ?></td>
                <td><?= htmlspecialchars($row['lote']) ?></td>
                <td>
                    <a class="action-btn edit-btn" href="editar.php?id=<?= $row['id_Producto'] ?>">Editar</a>
                    <a class="action-btn delete-btn" href="eliminar.php?id=<?= $row['id_Producto'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este producto?')">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
