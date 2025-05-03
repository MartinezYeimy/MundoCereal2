<?php
include 'Conexion_db.php'; // Conexión a la base de datos

$sql = "SELECT * FROM inventario";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .container {
            width: 90%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border-bottom: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f1f1f1;
            color: #333;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .button {
            display: inline-block;
            padding: 8px 15px;
            margin: 5px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .button:hover {
            background-color: #45a049;
        }
        .delete {
            background-color: #f44336;
        }
        .delete:hover {
            background-color: #e53935;
        }
        .add-button {
            background-color: #2196F3;
        }
        .add-button:hover {
            background-color: #1976D2;
        }
    </style>
</head>
<body>

<header>
    Inventario de Productos
</header>

<div class="container">
    <div style="text-align:right;">
        <a href="agregar.php" class="button add-button">Agregar Nuevo Producto</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Inventario</th>
                <th>Producto</th>
                <th>Gramaje</th>
                <th>Cantidad</th>
                
                <th>ID Producto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $row["id_inventario"] . "</td>
                        <td>" . $row["producto"] . "</td>
                        <td>" . $row["gramage"] . "</td>
                        <td>" . $row["cantidad"] . "</td>
                        
                        <td>" . $row["id_producto"] . "</td>
                        <td>
                            <a href='editar.php?id=" . $row["id_inventario"] . "' class='button'>Editar</a>
                            <a href='eliminar.php?id=" . $row["id_inventario"] . "' class='button delete' onclick=\"return confirm('¿Estás seguro que deseas eliminar este producto?');\">Eliminar</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No hay productos en el inventario.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
