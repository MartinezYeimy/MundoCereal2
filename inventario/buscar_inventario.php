<?php
include 'Conexion_db.php';

$q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

if ($q != '') {
    $sql = "SELECT * FROM inventario WHERE producto LIKE '%$q%' OR gramaje LIKE '%$q%'";
} else {
    $sql = "SELECT * FROM inventario";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row["id_Inventario"] . "</td>
            <td>" . $row["producto"] . "</td>
            <td>" . $row["gramaje"] . "</td>
            <td>" . $row["cantidad"] . "</td>
            <td>$" . number_format($row["precio_unitario"], 2) . "</td>
            <td>$" . number_format($row["precio_total"], 2) . "</td>
            <td>" . $row["id_Producto"] . "</td>
            <td>
                <a href='editar.php?id=" . $row["id_Inventario"] . "' class='button edit'>Editar</a>
                <a href='eliminar.php?id=" . $row["id_Inventario"] . "' class='button delete' onclick=\"return confirm('¿Estás seguro que deseas eliminar este producto?');\">Eliminar</a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='8' style='text-align:center; padding:20px;'>No se encontraron resultados.</td></tr>";
}
?>
