<?php
include '../dt_base/Conexion_db.php'; // Conexión a la base de datos

// Recuperar todos los pedidos
$sql_pedidos = "SELECT p.id_pedido, p.fecha_pedido, c.razon_social 
                FROM Pedido p
                JOIN Cliente c ON p.id_cliente = c.nit";
$pedidos_result = $conn->query($sql_pedidos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pedidos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f5f5f5;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Lista de Pedidos</h2>

<?php
if ($pedidos_result->num_rows > 0) {
    echo "<table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Ver Detalles</th>
                </tr>
            </thead>
            <tbody>";

    while ($pedido = $pedidos_result->fetch_assoc()) {
        $id_pedido = $pedido['id_pedido'];
        $fecha_pedido = $pedido['fecha_pedido'];
        $razon_social = $pedido['razon_social'];
        
        // Mostrar el enlace de detalles y depurar el ID del pedido
        echo "<tr>
                <td>" . $id_pedido . "</td>
                <td>" . $fecha_pedido . "</td>
                <td>" . $razon_social . "</td>
                <td><a href='ver_detalle_pedido.php?id_pedido=$id_pedido' class='btn'>Ver Detalles</a></td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>No se encontraron pedidos.</p>";
}

$conn->close();
?>

</body>
</html>
