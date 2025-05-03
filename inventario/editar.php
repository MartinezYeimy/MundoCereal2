<?php
include '../dt_base/Conexion_db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_inventario = intval($_GET['id']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nueva_cantidad = intval($_POST['nueva_cantidad']);

        if ($nueva_cantidad > 0) {
            $sql_update_cantidad = "UPDATE inventario 
                                    SET cantidad = cantidad + ?
                                    WHERE id_Inventario = ?";
            $stmt_cantidad = $conn->prepare($sql_update_cantidad);
            $stmt_cantidad->bind_param("ii", $nueva_cantidad, $id_inventario);
            $stmt_cantidad->execute();
        }

        header("Location: inventario.php?mensaje=actualizado");
        exit();
    }

    // Cargar cantidad actual
    $sql = "SELECT cantidad FROM inventario WHERE id_Inventario = $id_inventario";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Inventario no encontrado.";
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
    <title>Sumar Cantidad</title>
    <style>
        <?php include 'estilos.css'; ?>
    </style>
</head>
<body>

<header>Sumar Cantidad</header>

<div class="container">
    <form method="POST">
        <!-- Cantidad actual (solo visual) -->
        <label>Cantidad Actual:</label>
        <input type="number" value="<?php echo $row['cantidad']; ?>" disabled>

        <!-- Nueva cantidad a sumar -->
        <label>Sumar Cantidad:</label>
        <input type="number" name="nueva_cantidad" value="0" min="1" required>

        <!-- Botón para guardar -->
        <input type="submit" value="Actualizar Cantidad" class="button">

        <!-- Cancelar -->
        <a href="inventario.php" class="button">Cancelar</a>
    </form>
</div>

</body>
</html>
