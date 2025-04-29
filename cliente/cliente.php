<?php
include '../dt_base/Conexion_db.php';

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nit = $_POST['nit'];
    $razon_social = $_POST['razon_social'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];

    // Validar si el NIT ya existe
    $verificar = $conn->query("SELECT nit FROM Cliente WHERE nit = '$nit'");

    if ($verificar->num_rows > 0) {
        $mensaje = "⚠️ El NIT ya está registrado. Intenta con otro.";
        $tipoMensaje = "error";
    } else {
        $sql = "INSERT INTO Cliente (nit, razon_social, correo, direccion)
                VALUES ('$nit', '$razon_social', '$correo', '$direccion')";

        if ($conn->query($sql) === TRUE) {
            $mensaje = "✅ Cliente registrado correctamente.";
            $tipoMensaje = "success";
        } else {
            $mensaje = "❌ Error al registrar cliente: " . $conn->error;
            $tipoMensaje = "error";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #fff;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            width: 100%;
            max-width: 420px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #444;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .mensaje {
            margin-top: 20px;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
                .volver-btn {
            display: inline-block;
            text-align: center;
            margin-top: 10px;
            background-color: #6c757d;
            color: white;
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .volver-btn:hover {
            background-color: #5a6268;
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registrar Cliente</h2>
        <form method="POST" action="">
            <label for="nit">NIT:</label>
            <input type="text" id="nit" name="nit" required>

            <label for="razon_social">Razón Social:</label>
            <input type="text" id="razon_social" name="razon_social" required>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>

            <input type="submit" value="Registrar">
            
            <a class="volver-btn" href="ver_clientes.php">⟵ Volver</a>

    
        </form>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?= $tipoMensaje ?>">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
