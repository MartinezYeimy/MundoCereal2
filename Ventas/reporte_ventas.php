<?php
include '../dt_base/Conexion_db.php';

$fechaInicio = $_GET['fecha_inicio'] ?? '';
$fechaFin = $_GET['fecha_fin'] ?? '';

$ventas = [];
$totalGeneral = 0;
$resumenProductos = [];

if ($fechaInicio && $fechaFin) {
    // Detalle por fecha y producto
    $sql = "SELECT fecha, producto, SUM(cantidad) AS total_vendido
            FROM ventas v
            JOIN detalle_venta d ON v.id = d.venta_id
            WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin'
            GROUP BY fecha, producto
            ORDER BY fecha DESC";

    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $ventas[] = $row;
        $totalGeneral += $row['total_vendido'];

        // Acumulado por producto
        $producto = $row['producto'];
        $resumenProductos[$producto] = ($resumenProductos[$producto] ?? 0) + $row['total_vendido'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            text-align: center;
            background: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        input[type="date"] {
            padding: 8px;
            margin: 0 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            padding: 8px 16px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .export-buttons {
            text-align: center;
            margin: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin: 20px 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        thead {
            background-color: #f8f9fa;
        }

        p {
            text-align: right;
            font-weight: bold;
        }

        canvas {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<h2>📊 Reporte de Ventas</h2>

<form method="GET">
    Desde: <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($fechaInicio) ?>" required>
    Hasta: <input type="date" name="fecha_fin" value="<?= htmlspecialchars($fechaFin) ?>" required>
    <button type="submit">Filtrar</button>
</form>

<?php if ($fechaInicio && $fechaFin): ?>
    <div class="export-buttons">
        <button onclick="exportTableToExcel('tabla-reporte', 'reporte_ventas')">📥 Exportar a Excel</button>
        <button onclick="exportToPDF()">📄 Exportar a PDF</button>
    </div>

    <div id="reporte-pdf">
        <table id="tabla-reporte">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Total Vendido</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $venta): ?>
                    <tr>
                        <td><?= $venta['fecha'] ?></td>
                        <td><?= $venta['producto'] ?></td>
                        <td><?= number_format($venta['total_vendido'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p>Total General: S/ <?= number_format($totalGeneral, 2) ?></p>
    </div>

    <h3 style="margin-top: 40px;">Resumen por Producto</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Total Vendido</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resumenProductos as $producto => $total): ?>
                <tr>
                    <td><?= $producto ?></td>
                    <td><?= number_format($total, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3 style="text-align:center;">📈 Gráfico de Ventas por Producto</h3>
    <canvas id="ventasChart" height="100"></canvas>

    <script>
        const ctx = document.getElementById('ventasChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($resumenProductos)) ?>,
                datasets: [{
                    label: 'Total Vendido',
                    data: <?= json_encode(array_values($resumenProductos)) ?>,
                    backgroundColor: '#28a745',
                    borderColor: '#1e7e34',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Ventas por Producto',
                        font: {
                            size: 18
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Unidades Vendidas' }
                    },
                    x: {
                        title: { display: true, text: 'Producto' }
                    }
                }
            }
        });
    </script>

<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function exportTableToExcel(tableID, filename = '') {
    const dataType = 'application/vnd.ms-excel';
    const tableSelect = document.getElementById(tableID);
    const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    filename = filename ? filename + '.xls' : 'excel_data.xls';
    const downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    if (navigator.msSaveOrOpenBlob) {
        const blob = new Blob(['\ufeff', tableHTML], { type: dataType });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}

function exportToPDF() {
    const element = document.getElementById('reporte-pdf');
    const opt = {
        margin:       0.5,
        filename:     'reporte_ventas.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>

</body>
</html>

<?php $conn->close(); ?>
