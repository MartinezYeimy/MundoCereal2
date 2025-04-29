<?php
include '../dt_base/Conexion_db.php'; // Tu conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = (int)$_POST['id_cliente'];
    $productos = $_POST['id_producto'];
    $cantidades = $_POST['cantidad_producto'];

    $fecha_pedido = date('Y-m-d');
    $sql_insert_pedido = "INSERT INTO Pedido (fecha_pedido, id_cliente) VALUES ('$fecha_pedido', $id_cliente)";

    if ($conn->query($sql_insert_pedido) === TRUE) {
        $id_pedido = $conn->insert_id;
        $total_general = 0;

        for ($i = 0; $i < count($productos); $i++) {
            $id_producto = (int)$productos[$i];
            $cantidad = (int)$cantidades[$i];

            $sql_precio = "SELECT precio_unitario FROM Producto WHERE id_Producto = $id_producto";
            $result = $conn->query($sql_precio);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $precio_unitario = $row['precio_unitario'];
                $total_producto = $precio_unitario * $cantidad;

                $sql_insert_detalle = "INSERT INTO Pedido_Detalle (id_pedido, id_producto, cantidad, precio_unitario, total_producto)
                                       VALUES ($id_pedido, $id_producto, $cantidad, $precio_unitario, $total_producto)";
                if ($conn->query($sql_insert_detalle)) {
                    $total_general += $total_producto;
                } else {
                    echo "❌ Error guardando detalle: " . $conn->error . "<br>";
                }
            } else {
                echo "❌ Producto con ID $id_producto no encontrado.<br>";
            }
        }

        echo "<h3 style='text-align:center; color:#4CAF50;'>✅ Pedido registrado correctamente</h3>";

        echo "<table style='width:100%; border-collapse:collapse; margin-top:20px; font-family:Arial, sans-serif;'>";
        echo "<thead style='background-color:#f4f4f4;'>
                <tr style='text-align:center;'>
                    <th style='padding:12px; border:1px solid #ddd;'>Producto</th>
                    <th style='padding:12px; border:1px solid #ddd;'>Cantidad</th>
                    <th style='padding:12px; border:1px solid #ddd;'>Precio Unitario</th>
                    <th style='padding:12px; border:1px solid #ddd;'>Total</th>
                </tr>
              </thead>";
        echo "<tbody>";
        
        for ($i = 0; $i < count($productos); $i++) {
            $id_producto = (int)$productos[$i];
            $cantidad = (int)$cantidades[$i];
        
            $sql_precio = "SELECT nombre, precio_unitario FROM Producto WHERE id_Producto = $id_producto";
            $result = $conn->query($sql_precio);
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $precio_unitario = $row['precio_unitario'];
                $nombre_producto = $row['nombre'];
                $total_producto = $precio_unitario * $cantidad;
        
                echo "<tr style='text-align:center;'>
                        <td style='padding:10px; border:1px solid #ddd;'>$nombre_producto</td>
                        <td style='padding:10px; border:1px solid #ddd;'>$cantidad</td>
                        <td style='padding:10px; border:1px solid #ddd;'>$" . number_format($precio_unitario, 0, ',', '.') . "</td>
                        <td style='padding:10px; border:1px solid #ddd;'>$" . number_format($total_producto, 0, ',', '.') . "</td>
                      </tr>";
            }
        }
        
        echo "</tbody></table>";
        
        echo "<div style='margin-top:20px; text-align:center; font-size:18px; font-weight:bold; color:#4CAF50;'>
                <p><strong>Total del Pedido: </strong>$" . number_format($total_general, 0, ',', '.') . "</p>
                <a href='pedido.php' style='text-decoration:none; background-color:#2196F3; color:white; padding:10px 20px; border-radius:5px; font-size:16px;'>Registrar otro pedido</a>
              </div>";
        
    } else {
        echo "<p>❌ Error al crear el pedido: " . $conn->error . "</p>";
    }

    $conn->close();
    echo '<br><a href="pedido.php" style="text-decoration:none; color:blue;"></a>';
    exit();
}

$clientes = $conn->query("SELECT * FROM Cliente");
$productos = $conn->query("SELECT * FROM Producto");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        form {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        select, input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f5f5f5;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 18px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: 100%;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-add {
            background-color: #2196F3;
            margin-top: 15px;
            width: auto;
        }
        .btn-delete {
            background-color: #f44336;
            border: none;
            padding: 5px 10px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-delete:hover {
            background-color: #d32f2f;
        }
    </style>

    <script>
    // Objeto con datos de todos los clientes
    const clientesData = {
        <?php
        $clientesData = $conn->query("SELECT * FROM Cliente");
        while ($cliente = $clientesData->fetch_assoc()) {
            $id = $cliente['nit'];
            $json = json_encode($cliente, JSON_UNESCAPED_UNICODE);
            echo "$id: $json,";
        }
        ?>
    };
    
    </script>
</head>
<body>



<form action="pedido.php" method="POST">
    <label for="cliente">Selecciona un cliente:</label>
    <select name="id_cliente" id="cliente" required>
        <option value="">-- Selecciona --</option>
        <?php
        // Reiniciar puntero de resultado
        $clientes->data_seek(0);
        while ($cliente = $clientes->fetch_assoc()): ?>
            <option value="<?= $cliente['nit'] ?>"><?= htmlspecialchars($cliente['razon_social']) ?></option>
        <?php endwhile; ?>
    </select>

    <div id="infoCliente" style="margin-top: 15px; background:#eef; padding:10px; border-radius:5px; display:none;">
        <h4>Información del Cliente:</h4>
        <p><strong>NIT:</strong> <span id="nitCliente"></span></p>
        <p><strong>Razón Social:</strong> <span id="razonCliente"></span></p>
        <p><strong>Dirección:</strong> <span id="direccionCliente"></span></p>
        <p><strong>Teléfono:</strong> <span id="telefonoCliente"></span></p>
    </div>

    <table id="productos">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select name="id_producto[]" onchange="validarProductos()" required>
                        <option value="">-- Selecciona --</option>
                        <?php 
                        $productos_inicial = $conn->query("SELECT * FROM Producto");
                        while ($producto = $productos_inicial->fetch_assoc()): ?>
                            <option value="<?= $producto['id_Producto'] ?>"><?= htmlspecialchars($producto['nombre']) ?> - $<?= $producto['precio_unitario'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </td>
                <td><input type="number" name="cantidad_producto[]" min="1" required></td>
                <td><button type="button" class="btn-delete" onclick="eliminarProducto(this)">Eliminar</button></td>
            </tr>
        </tbody>
    </table>

    <button type="button" class="btn btn-add" onclick="agregarProducto()">➕ Agregar Producto</button>

    <input type="submit" value="Registrar Pedido" class="btn">
</form>

<script>
function agregarProducto() {
    var tbody = document.querySelector("#productos tbody");
    var newRow = document.createElement("tr");
    newRow.innerHTML = `
        <td>
            <select name="id_producto[]" onchange="validarProductos()" required>
                <option value="">-- Selecciona --</option>
                <?php
                $productos_js = $conn->query("SELECT * FROM Producto");
                while ($producto = $productos_js->fetch_assoc()) {
                    echo "<option value='" . $producto['id_Producto'] . "'>" . htmlspecialchars($producto['nombre']) . " - $" . $producto['precio_unitario'] . "</option>";
                }
                ?>
            </select>
        </td>
        <td><input type="number" name="cantidad_producto[]" min="1" required></td>
        <td><button type="button" class="btn-delete" onclick="eliminarProducto(this)">Eliminar</button></td>
    `;
    tbody.appendChild(newRow);
    validarProductos();
}

function eliminarProducto(button) {
    button.closest('tr').remove();
    validarProductos();
}

function validarProductos() {
    var selects = document.querySelectorAll('select[name="id_producto[]"]');
    var valores = [];

    selects.forEach(function(select) {
        if (select.value) {
            if (valores.includes(select.value)) {
                alert('⚠️ Este producto ya fue seleccionado. Por favor elige otro.');
                select.value = '';
            } else {
                valores.push(select.value);
            }
        }
    });
}

document.getElementById('cliente').addEventListener('change', function () {
    const id = this.value;
    const infoDiv = document.getElementById('infoCliente');

    if (id && clientesData[id]) {
        const cliente = clientesData[id];
        document.getElementById('nitCliente').textContent = cliente.nit;
        document.getElementById('razonCliente').textContent = cliente.razon_social;
        document.getElementById('direccionCliente').textContent = cliente.direccion;
        document.getElementById('telefonoCliente').textContent = cliente.telefono;
        infoDiv.style.display = 'block';
    } else {
        infoDiv.style.display = 'none';
    }
});
</script>

</body>
</html>

</body>
</html>
