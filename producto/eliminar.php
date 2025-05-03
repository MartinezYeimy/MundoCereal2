<?php
include '../dt_base/Conexion_db.php';

$id = $_GET['id'];

$sql = "DELETE FROM producto WHERE id_Producto=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: mostrar.php");
?>
