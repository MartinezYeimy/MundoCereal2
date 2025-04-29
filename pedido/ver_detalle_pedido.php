<?php
include '../dt_base/Conexion_db.php'; // Conexión a la base de datos

// Recuperar el ID del pedido desde la URL
$id_pedido = isset($_GET['id_pedido']) ? (int)$_GET['id_pedido'] : 0;

if ($id_pedido == 0) {
    echo "❌ ID de pedido no válido.";
    exit;
}

// Recuperar los detalles del pedido
$sql_detalle = "SELECT pd.id_producto, p.nombre, pd.cantidad, pd.precio_unitario, pd.total_producto
                FROM Pedido_Detalle pd
                JOIN Producto p ON pd.id_producto = p.id_Producto
                WHERE pd.id_pedido = $id_pedido";
$detalle_result = $conn->query($sql_detalle);

// Recuperar información del pedido
$sql_pedido = "SELECT p.fecha_pedido, c.razon_social 
               FROM Pedido p 
               JOIN Cliente c ON p.id_cliente = c.nit 
               WHERE p.id_pedido = $id_pedido";
$pedido_result = $conn->query($sql_pedido);
$pedido = $pedido_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Pedido</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .pedido-info {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .pedido-info p {
            font-size: 16px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        table td {
            background-color: #f9f9f9;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .back-btn {
            background-color: #f44336;
        }
        .back-btn:hover {
            background-color: #e53935;
        }
        .footer {
            text-align: center;
            padding: 20px;
            margin-top: 30px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Detalles del Pedido #<?= $id_pedido ?></h1>
    
    <div class="pedido-info">
        <p><strong>Fecha del Pedido:</strong> <?= $pedido['fecha_pedido'] ?></p>
        <p><strong>Cliente:</strong> <?= $pedido['razon_social'] ?></p>
    </div>

    <h2>Productos en el Pedido</h2>

    <?php if ($detalle_result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total Producto</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($detalle = $detalle_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $detalle['id_producto'] ?></td>
                        <td><?= $detalle['nombre'] ?></td>
                        <td><?= $detalle['cantidad'] ?></td>
                        <td><?= number_format($detalle['precio_unitario'], 2, ',', '.') ?></td>
                        <td><?= number_format($detalle['total_producto'], 2, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron detalles para este pedido.</p>
    <?php endif; ?>

    <div class="footer">
        <a href="lista_pedidos.php" class="btn back-btn"><i class="fas fa-arrow-left"></i> Regresar a la lista de pedidos</a>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
