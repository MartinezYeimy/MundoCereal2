<?php
include '../dt_base/Conexion_db.php';

if (isset($_GET['nit'])) {
    $nit = $_GET['nit'];
    $conn->query("DELETE FROM Cliente WHERE nit = '$nit'");
    header("Location: ver_clientes.php");
    exit();
}
?>
