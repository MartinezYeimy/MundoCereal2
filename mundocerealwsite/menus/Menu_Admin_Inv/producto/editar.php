<?php
include 'Conexion_db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM inventario WHERE id_inventario=$id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Producto no encontrado";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $producto = $_POST['producto'];
    $gramage = $_POST['gramage'];
    $cantidad = $_POST['cantidad'];
    
    $id_producto = $_POST['id_producto'];

    $sql = "UPDATE inventario SET 
            producto='$producto', 
            gramage='$gramage', 
            cantidad='$cantidad', 
             
            id_producto='$id_producto' 
            WHERE id_inventario=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: inventario.php");
    } else {
        echo "Error actualizando: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="./estilosp2.css">
</head>
<body>

<header>Editar Producto</header>

<div class="container">
    <form action="editar.php" method="post">
        <input type="hidden" name="id" value="<?php echo $row['id_inventario']; ?>">

        <label>Producto:</label>
        <input type="text" name="producto" value="<?php echo $row['producto']; ?>" required>

        <label>Gramaje:</label>
        <input type="text" name="gramage" value="<?php echo $row['gramage']; ?>" required>

        <label>Cantidad:</label>
        <input type="number" name="cantidad" value="<?php echo $row['cantidad']; ?>" required>

        
        <label>ID Producto:</label>
        <input type="number" name="id_producto" value="<?php echo $row['id_producto']; ?>" required>

        <input type="submit" value="Actualizar Producto" class="button">
        <a href="inventario.php" class="button">Cancelar</a>
    </form>
</div>

</body>
</html>
