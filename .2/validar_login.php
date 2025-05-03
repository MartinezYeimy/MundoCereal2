<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "mundocereal");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Captura de datos del formulario
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

// Consulta para verificar el usuario
$sql = "SELECT * FROM usuario WHERE nombre = '$usuario' AND contraseña = '$contrasena'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    // Usuario encontrado
    $fila = $resultado->fetch_assoc();
    
    if ($fila['nombre'] == "Blanca") {
        header("Location: menus/Menu_Admin/index.html");
        exit();
    } elseif ($fila['nombre'] == "Santiago") {
        header("Location: menus/Menu_Admin_Inv/index.html");
        exit();
    } else {
        echo "Usuario no autorizado.";
    }
} else {
    // Usuario no encontrado
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='index.html';</script>";
}

$conexion->close();
?>
