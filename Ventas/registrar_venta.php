<?php
include '../dt_base/Conexion_db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fecha = $_POST['fecha'];
    $comprobante_tipo = $_POST['comprobante_tipo'];
    $comprobante_numero = $_POST['comprobante_numero'];
    $cliente_nombre = $_POST['cliente_nombre'];
    $cliente_documento = $_POST['cliente_documento'];
    $productos = $_POST['producto'];
    $cantidades = $_POST['cantidad'];
    $precios = $_POST['precio_unitario'];
    $subtotales = $_POST['subtotal'];

    $total = array_sum($subtotales);

    $sqlVenta = "INSERT INTO ventas (fecha, comprobante_tipo, comprobante_numero, cliente_nombre, cliente_documento, total)
                 VALUES ('$fecha', '$comprobante_tipo', '$comprobante_numero', '$cliente_nombre', '$cliente_documento', $total)";

    if ($conn->query($sqlVenta) === TRUE) {
        $venta_id = $conn->insert_id;

        for ($i = 0; $i < count($productos); $i++) {
            $producto = $productos[$i];
            $cantidad = $cantidades[$i];
            $precio_unitario = $precios[$i];
            $subtotal = $subtotales[$i];

            $sqlDetalle = "INSERT INTO detalle_venta (venta_id, producto, cantidad, precio_unitario, subtotal)
                           VALUES ($venta_id, '$producto', $cantidad, $precio_unitario, $subtotal)";
            $conn->query($sqlDetalle);
        }

        echo "<p style='color:green;'>✅ Venta registrada exitosamente.</p>";
    } else {
        echo "<p style='color:red;'>❌ Error: " . $conn->error . "</p>";
    }
}

// OBTENER productos y construir arreglo de precios por ID
$productos_query = "SELECT * FROM producto";
$productos_result = $conn->query($productos_query);
$precios = [];
while ($row = $productos_result->fetch_assoc()) {
    $precios[$row['id_Producto']] = $row['precio_unitario'];
}
$productos_result->data_seek(0); // reiniciar puntero
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Venta</title>
    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        /* BODY */
        body {
            background-color: #f4f7fa;
            padding: 30px;
            font-size: 16px;
        }

        /* TÍTULOS */
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        /* FORMULARIO */
        form {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }

        /* LABELS Y ENTRADAS */
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        input[type="datetime-local"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
        }

        /* BOTONES */
        button {
            background-color: #007bff;
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        button[type="submit"] {
            background-color: #28a745;
            width: 100%;
            font-size: 1.2rem;
            padding: 15px;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }

        /* CONTAINER DE PRODUCTOS */
        #productos-container {
            margin-top: 20px;
        }

        #productos-container div {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
            align-items: center;
        }

        #productos-container select,
        #productos-container input {
            flex-grow: 1;
        }

        button[type="button"] {
            background-color: #dc3545;
            padding: 8px 15px;
            font-size: 0.9rem;
        }

        button[type="button"]:hover {
            background-color: #c82333;
        }

        /* TOTAL */
        #total {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            padding-top: 10px;
            display: block;
            text-align: center;
        }

        span {
            font-size: 1rem;
            color: #888;
        }

        /* ESTILOS DE MENSAJES */
        p {
            font-size: 1rem;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border-radius: 6px;
        }

        p[style*="color:green"] {
            background-color: #e7f9e7;
            color: #28a745;
        }

        p[style*="color:red"] {
            background-color: #f8d7da;
            color: #dc3545;
        }
    </style>
</head>
<body>

<h2>Registrar Venta</h2>

<form method="POST" onsubmit="return calcularTotales();">
    <label>Fecha:</label>
    <input type="datetime-local" name="fecha" required>

    <label>Tipo de Comprobante:</label>
    <input type="text" name="comprobante_tipo" required>

    <label>Número de Comprobante:</label>
    <input type="text" name="comprobante_numero" required>

    <label>Cliente:</label>
    <input type="text" name="cliente_nombre" required>

    <label>Documento del Cliente:</label>
    <input type="text" name="cliente_documento" required>

    <div id="productos-container"></div>
    <button type="button" onclick="agregarProducto()">+ Agregar Producto</button>

    <div>
        <span>Total: S/ <span id="total">0.00</span></span>
    </div>

    <input type="submit" value="Registrar Venta">
</form>

<script>
const precios = <?= json_encode($precios) ?>;
let index = 0;

function agregarProducto() {
    const container = document.getElementById('productos-container');
    const div = document.createElement('div');
    div.innerHTML = `
        <select name="producto[]" onchange="setPrecio(this)" required>
            <option value="">-- Selecciona --</option>
            <?php
            $productos_result->data_seek(0);
            while ($row = $productos_result->fetch_assoc()):
                $id = $row['id_Producto'];
                $nombre = htmlspecialchars($row['nombre']);
                $gramaje = $row['gramaje'];
                $descripcion = "$nombre - $gramaje g";
            ?>
            <option value="<?= $id ?>"><?= $descripcion ?></option>
            <?php endwhile; ?>
        </select>

        <input type="number" name="cantidad[]" oninput="calcularTotales()" placeholder="Cantidad" required>
        <input type="number" name="precio_unitario[]" step="0.01" oninput="calcularTotales()" placeholder="Precio" required readonly>
        <input type="hidden" name="subtotal[]" value="0">
        <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
    `;
    container.appendChild(div);
    index++;
}

function setPrecio(selectElem) {
    const productoId = selectElem.value;
    const precio = precios[productoId] || 0;
    const grupo = selectElem.parentNode;
    grupo.querySelector('input[name="precio_unitario[]"]').value = precio;
    calcularTotales();
}

function eliminarProducto(button) {
    button.parentElement.remove();
    calcularTotales();
}

function calcularTotales() {
    const contenedor = document.getElementById('productos-container');
    const grupos = contenedor.querySelectorAll('div');
    let total = 0;
    grupos.forEach(grupo => {
        const cantidad = parseFloat(grupo.querySelector('input[name="cantidad[]"]').value) || 0;
        const precio = parseFloat(grupo.querySelector('input[name="precio_unitario[]"]').value) || 0;
        const subtotal = cantidad * precio;
        grupo.querySelector('input[name="subtotal[]"]').value = subtotal.toFixed(2);
        total += subtotal;
    });
    document.getElementById('total').textContent = total.toFixed(2);
    return true;
}
</script>

</body>
</html>

<?php $conn->close(); ?>
