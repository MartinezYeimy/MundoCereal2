<?php
include '../dt_base/Conexion_db.php';

$sql = "SELECT * FROM Cliente";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes Registrados</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7fb;
            padding: 40px;
        }

        h2 {
            text-align: center;
        }

        .top-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-link {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .btn-link:hover {
            background-color: #0056b3;
        }

        table {
            margin: auto;
            border-collapse: collapse;
            width: 90%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        a.button {
            padding: 6px 12px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .edit-btn { background-color: #28a745; }
        .delete-btn { background-color: #dc3545; }

        .button:hover { opacity: 0.85; }
    </style>
</head>
<body>
    <h2>Clientes Registrados</h2>

    <div class="top-button">
        <a class="btn-link" href="cliente.php">➕ Registrar Nuevo Cliente</a>
    </div>

    <table>
        <tr>
            <th>NIT</th>
            <th>Razón Social</th>
            <th>Correo</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $fila['nit'] ?></td>
            <td><?= $fila['razon_social'] ?></td>
            <td><?= $fila['correo'] ?></td>
            <td><?= $fila['direccion'] ?></td>
            <td>
                <a class="button edit-btn" href="editar_cliente.php?nit=<?= $fila['nit'] ?>">Editar</a>
                <a class="button delete-btn" href="eliminar_cliente.php?nit=<?= $fila['nit'] ?>" onclick="return confirm('¿Estás seguro de eliminar este cliente?');">Eliminar</a>
            </td>
        </tr>
        
        <?php endwhile; ?>
        
    </table>
</body>
</html>

<?php $conn->close(); ?>
