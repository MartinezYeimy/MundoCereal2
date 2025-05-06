<?php
include '../dt_base/Conexion_db.php';

$venta_id = isset($_GET['venta_id']) ? intval($_GET['venta_id']) : 0;

$sql = "SELECT producto, cantidad, precio_unitario, subtotal 
        FROM detalle_venta 
        WHERE venta_id = $venta_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Venta</title>
    <style>
        /* Estilo general */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 30px;
            font-size: 24px;
        }

        p {
            text-align: center;
            margin-top: 10px;
            font-size: 16px;
        }

        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Tabla */
        table {
            border-collapse: collapse;
            width: 70%;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td {
            color: #555;
        }

        /* Efectos hover */
        tr:hover {
            background-color: #f1f1f1;
        }

        /* Estilo de la fila vacía */
        .no-data {
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>

<h2>Detalle de la Venta #<?= $venta_id ?></h2>
<p><a href="ventas.php">← Volver al listado</a></p>

<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['producto']) ?></td>
                    <td><?= $row['cantidad'] ?></td>
                    <td><?= number_format($row['precio_unitario'], 2) ?></td>
                    <td><?= number_format($row['subtotal'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="no-data">No hay productos para esta venta.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

<?php $conn->close(); ?>
