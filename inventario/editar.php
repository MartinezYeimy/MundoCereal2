<?php
include 'Conexion_db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_inventario = intval($_GET['id']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['producto'];
        $gramaje = $_POST['gramaje'];
        $precio_unitario = $_POST['precio_unitario'];
        $nueva_cantidad = intval($_POST['nueva_cantidad']); // Nueva cantidad a sumar

        // Actualizar datos en la tabla producto
        $sql_update_producto = "UPDATE producto p 
                                INNER JOIN inventario i ON p.id_Producto = i.id_Producto 
                                SET p.nombre = ?, p.gramaje = ?, p.precio_unitario = ?
                                WHERE i.id_Inventario = ?";
        $stmt_producto = $conn->prepare($sql_update_producto);
        $stmt_producto->bind_param("ssdi", $nombre, $gramaje, $precio_unitario, $id_inventario);
        $stmt_producto->execute();

        // Actualizar cantidad en la tabla inventario
        if ($nueva_cantidad > 0) {
            $sql_update_cantidad = "UPDATE inventario 
                                    SET cantidad = cantidad + ?
                                    WHERE id_Inventario = ?";
            $stmt_cantidad = $conn->prepare($sql_update_cantidad);
            $stmt_cantidad->bind_param("ii", $nueva_cantidad, $id_inventario);
            $stmt_cantidad->execute();
        }

        header("Location: inventario.php");
        exit();
    }

    // Cargar los datos actuales
    $sql = "SELECT p.*, i.cantidad 
            FROM inventario i 
            INNER JOIN producto p ON i.id_Producto = p.id_Producto 
            WHERE i.id_Inventario = $id_inventario";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit;
    }
} else {
    echo "ID no especificado o inválido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <style>
        <?php include 'estilos.css'; ?>
    </style>
</head>
<body>

<header>Editar Producto</header>

<div class="container">
    <form method="POST">
        <!-- Nombre del Producto -->
        <label>Nombre del Producto:</label>
        <input type="text" name="producto" value="<?php echo htmlspecialchars($row['nombre']); ?>" required>

        <!-- Gramaje -->
        <label>Gramaje:</label>
        <input type="text" name="gramaje" value="<?php echo htmlspecialchars($row['gramaje']); ?>" required>

        <!-- Precio Unitario -->
        <label>Precio Unitario:</label>
        <input type="number" step="0.01" name="precio_unitario" value="<?php echo htmlspecialchars($row['precio_unitario']); ?>" required>

        <!-- Cantidad actual -->
        <label>Cantidad Actual:</label>
        <input type="number" value="<?php echo $row['cantidad']; ?>" disabled>

        <!-- Nueva cantidad para sumar -->
        <label>Sumar Cantidad:</label>
        <input type="number" name="nueva_cantidad" value="0" min="0">

        <!-- Botón para actualizar -->
        <input type="submit" value="Actualizar Producto" class="button">

        <!-- Enlace para cancelar -->
        <a href="inventario.php" class="button">Cancelar</a>
    </form>
</div>

</body>
</html>
