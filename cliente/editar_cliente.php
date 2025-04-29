<?php
include '../dt_base/Conexion_db.php';

if (isset($_GET['nit'])) {
    $nit = $_GET['nit'];

    $query = $conn->query("SELECT * FROM Cliente WHERE nit = '$nit'");
    $cliente = $query->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nit = $_POST['nit'];
    $razon_social = $_POST['razon_social'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];

    $conn->query("UPDATE Cliente SET razon_social='$razon_social', correo='$correo', direccion='$direccion' WHERE nit='$nit'");

    header("Location: ver_clientes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
</head>
<body>
    <h2>Editar Cliente</h2>
    <form method="POST">
        <input type="hidden" name="nit" value="<?= $cliente['nit'] ?>">

        <label>Razón Social:</label>
        <input type="text" name="razon_social" value="<?= $cliente['razon_social'] ?>" required><br><br>

        <label>Correo:</label>
        <input type="email" name="correo" value="<?= $cliente['correo'] ?>" required><br><br>

        <label>Dirección:</label>
        <input type="text" name="direccion" value="<?= $cliente['direccion'] ?>" required><br><br>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
