<?php
include '../dt_base/Conexion_db.php';

// Verificamos si se está buscando un producto
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

// Hacemos el INNER JOIN para traer también nombre y gramaje y aplicamos la búsqueda si se ingresó algo
$sql = "SELECT 
            i.id_Inventario, 
            i.cantidad, 
            i.precio_unitario, 
            i.precio_total, 
            i.id_producto, 
            p.nombre AS producto_nombre, 
            p.gramaje AS producto_gramaje
        FROM inventario i
        INNER JOIN producto p ON i.id_producto = p.id_producto
        WHERE p.nombre LIKE '%$buscar%' OR p.gramaje LIKE '%$buscar%'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Productos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(135deg, #43cea2, #185a9d);
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .add-button {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .add-button:hover {
            background: #2c80b4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        th {
            background-color: #f8f5f5;
            font-weight: bold;
        }

        tr:hover {
            background-color: #faf6f6;
        }

        .button {
            padding: 8px 12px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            margin: 2px;
            display: inline-block;
        }

        .edit {
            background: #43cea2;
        }

        .edit:hover {
            background: #36b191;
        }

        .delete {
            background: #e74c3c;
        }

        .delete:hover {
            background: #c0392b;
        }

        .search-form {
            margin-bottom: 20px;
            text-align: right;
        }

        .search-form input[type="text"] {
            padding: 8px;
            width: 200px;
            border-radius: 8px;
        }

        .search-form button {
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            border-radius: 8px;
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            td {
                position: relative;
                padding-left: 50%;
                margin-bottom: 12px;
                text-align: left;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                font-weight: bold;
                color: #555;
            }
        }
    </style>
</head>
<body>

<header>
    Inventario de Productos
</header>

<div class="container">
    <!-- Formulario de búsqueda -->
    <div class="search-form">
    <a href="agregar.php" class="add-button">Agregar Nuevo Producto</a>
        <form method="get" action="">
            <input type="text" name="buscar" placeholder="Buscar producto..." value="<?php echo htmlspecialchars($buscar); ?>">
            <button type="submit">Buscar</button>
        </form>
        
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Inventario</th>
                <th>Producto</th>
                <th>Gramaje</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Precio Total</th>
                <th>ID Producto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $row["id_Inventario"] . "</td>
                        <td>" . htmlspecialchars($row["producto_nombre"]) . "</td>
                        <td>" . htmlspecialchars($row["producto_gramaje"]) . "</td>
                        <td>" . $row["cantidad"] . "</td>
                        <td>$" . number_format($row["precio_unitario"], 2) . "</td>
                        <td>$" . number_format($row["precio_total"], 2) . "</td>
                        <td>" . $row["id_producto"] . "</td>
                        <td>
                            <a href='editar.php?id=" . $row["id_Inventario"] . "' class='button edit'>Editar</a>
                            <a href='eliminar.php?id=" . $row["id_Inventario"] . "' class='button delete' onclick=\"return confirm('¿Estás seguro que deseas eliminar este producto?');\">Eliminar</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No hay productos que coincidan con la búsqueda.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
