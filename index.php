// login.php

// Conectar a MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prueba";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contraseña'];

    $stmt = $conn->prepare("SELECT contraseña, cargo FROM usuarios WHERE nombre = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if ($contrasena === $row['contraseña']) {
            $cargo = strtolower($row['cargo']);
            switch ($cargo) {
                case 'administrador':
                    header("Location: menus/Menu_Admin/index.html"); break;
                case 'administrador inventario':
                    header("Location: menus/Menu_Admin_Inv/index.html"); break;
                case 'administrador de materia':
                    header("Location: menus/Menu_Admin_Materia/index.html"); break;
                case 'vendedor':
                    header("Location: menus/Menu_Vendedor/index.html"); break;
                default:
                    echo "<script>alert('Cargo no válido'); window.location.href='login.php';</script>"; break;
            }
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location.href='login.php';</script>";
    }
}
