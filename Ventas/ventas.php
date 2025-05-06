<?php
include '../dt_base/Conexion_db.php';

$sql = "SELECT id, fecha, comprobante_tipo, comprobante_numero, cliente_nombre, cliente_documento, total 
        FROM ventas 
        ORDER BY fecha DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Ventas</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            font-size: 28px;
            color: #333;
        }

        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px 15px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        td a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        td a:hover {
            color: #388E3C;
        }

        .no-ventas {
            text-align: center;
            font-size: 18px;
            color: #e74c3c;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }

        .pagination a:hover {
            color: #388E3C;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Ventas Registradas</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>N° Comprobante</th>
                <th>Cliente</th>
                <th>Documento</th>
                <th>Total</th>
                <th>Detalles</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['fecha'])) ?></td>
                        <td><?= $row['comprobante_tipo'] ?></td>
                        <td><?= $row['comprobante_numero'] ?></td>
                        <td><?= $row['cliente_nombre'] ?></td>
                        <td><?= $row['cliente_documento'] ?></td>
                        <td><?= number_format($row['total'], 2) ?></td>
                        <td><a href="detalle_venta.php?venta_id=<?= $row['id'] ?>">Ver detalles</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="no-ventas">No hay ventas registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Opcional: Si usas paginación -->
    <div class="pagination">
        <a href="#">« Anterior</a>
        <a href="#">1</a>
        <a href="#">2</a>
        <a href="#">3</a>
        <a href="#">Siguiente »</a>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
