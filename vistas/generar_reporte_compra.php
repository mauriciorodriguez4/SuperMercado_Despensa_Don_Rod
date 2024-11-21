<?php
include('../conexion/conexion.php');

// Verificar el estado de orden en la URL y definir el próximo estado
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'none'; // Estado inicial: sin orden
$next_orden = $orden === 'none' ? 'asc' : ($orden === 'asc' ? 'desc' : 'none');

// Crear la consulta SQL según el estado de orden actual
if ($orden === 'asc') {
    $query = "SELECT * FROM detalles_compra ORDER BY fecha_compra ASC";
} elseif ($orden === 'desc') {
    $query = "SELECT * FROM detalles_compra ORDER BY fecha_compra DESC";
} else {
    $query = "SELECT * FROM detalles_compra"; // Sin orden
}
$resultado = mysqli_query($conn, $query);

// Incluir la librería TCPDF
require_once('../tcpdf/tcpdf.php');

// Crear un nuevo PDF
$pdf = new TCPDF();
$pdf->AddPage();

// Agregar el logo
$logo_path = '../img/logo.jpg'; // Cambia la ruta al lugar donde tengas el logo
$pdf->Image($logo_path, 10, 10, 30, 0, 'JPG'); // Logo en la esquina superior izquierda

// Establecer la fuente para el título
$pdf->SetFont('helvetica', '', 16);

// Calcular la posición para el título centrado en la página
$title = 'Informe General de Compras';
$title_width = $pdf->GetStringWidth($title) + 6; // Calculamos el ancho del título para centrarlo
$page_width = $pdf->getPageWidth();
$title_x = ($page_width - $title_width) / 2; // Calculamos la posición X para centrar el título

// Colocar el título debajo del logo y centrado en la página
$pdf->SetXY($title_x, 20);
$pdf->Cell(0, 10, $title, 0, 1, 'C');

// Usar Ln() para agregar espacio antes de la tabla
$pdf->Ln(10); // Esto agrega un pequeño espacio entre el título y la tabla

// Tabla de compras
$pdf->SetFont('helvetica', '', 10);

// Ajuste de celdas para nombre más largo y ajustabilidad
$pdf->Cell(40, 10, 'Producto', 1, 0, 'C');
$pdf->Cell(40, 10, 'Cantidad', 1, 0, 'C');
$pdf->Cell(40, 10, 'Subtotal', 1, 0, 'C');
$pdf->Cell(40, 10, 'Fecha Compra', 1, 1, 'C'); // Nueva columna de "Fecha Compra"

// Agregar datos de las compras al PDF
while ($detalle_compra = mysqli_fetch_assoc($resultado)) {
    // Obtener el nombre del producto desde la tabla de productos usando el ID
    $producto_id = $detalle_compra['producto_id'];
    $query_producto = "SELECT nombre FROM productos WHERE producto_id = $producto_id";
    $resultado_producto = mysqli_query($conn, $query_producto);
    $producto = mysqli_fetch_assoc($resultado_producto);

    // Mostrar el nombre del producto, cantidad y subtotal en la tabla
    $pdf->MultiCell(40, 10, htmlspecialchars($producto['nombre']), 1, 'C', 0, 0, '', '', true);
    $pdf->MultiCell(40, 10, htmlspecialchars($detalle_compra['cantidad']), 1, 'C', 0, 0, '', '', true);
    $pdf->MultiCell(40, 10, '$' . number_format($detalle_compra['subtotal'], 2), 1, 'C', 0, 0, '', '', true);
    
    // Mostrar la fecha de compra
    $pdf->MultiCell(40, 10, htmlspecialchars($detalle_compra['fecha_compra']), 1, 'C', 0, 1, '', '', true);

    // Salto de línea después de cada compra
    $pdf->Ln();
}

// Salvar o mostrar el PDF
$pdf->Output('reporte_compras.pdf', 'I'); // Muestra el PDF directamente en el navegador
exit;
?>
