<?php
$servername = "localhost"; // o el nombre de tu servidor
$username = "root"; // tu usuario de MySQL
$password = ""; // tu contraseña de MySQL
$database = "mundo cereal.1"; // tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
