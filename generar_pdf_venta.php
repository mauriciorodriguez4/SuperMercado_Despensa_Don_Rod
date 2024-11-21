<?php
include('conexion/conexion.php');

// Obtener el id de la venta desde la URL
$venta_id = isset($_GET['venta_id']) ? $_GET['venta_id'] : null;

if ($venta_id === null) {
    die('No se ha especificado un ID de venta.');
}

// Crear la consulta SQL con JOIN para obtener datos de la venta y detalle_venta específica
$query = "
    SELECT dv.*, v.fecha_venta, p.nombre AS producto_nombre 
    FROM detalle_ventas dv
    JOIN ventas v ON dv.venta_id = v.venta_id
    JOIN productos p ON dv.producto_id = p.producto_id
    WHERE v.venta_id = $venta_id
";

$resultado = mysqli_query($conn, $query);

// Verificar si la venta existe
if (mysqli_num_rows($resultado) == 0) {
    die('Venta no encontrada.');
}

// Incluir la librería TCPDF
require_once('tcpdf/tcpdf.php');

// Crear un nuevo PDF
$pdf = new TCPDF();
$pdf->AddPage();

// Agregar el logo
$logo_path = 'img/logo.jpg'; // Cambia la ruta al lugar donde tengas el logo
$pdf->Image($logo_path, 10, 10, 30, 0, 'JPG'); // Logo en la esquina superior izquierda

// Establecer la fuente para el título
$pdf->SetFont('helvetica', '', 16);

// Calcular la posición para el título centrado en la página
$title = 'Ticket de Venta';
$title_width = $pdf->GetStringWidth($title) + 6; // Calculamos el ancho del título para centrarlo
$page_width = $pdf->getPageWidth();
$title_x = ($page_width - $title_width) / 2; // Calculamos la posición X para centrar el título

// Colocar el título debajo del logo y centrado en la página
$pdf->SetXY($title_x, 20);
$pdf->Cell(0, 10, $title, 0, 1, 'C');

// Usar Ln() para agregar espacio antes de la tabla
$pdf->Ln(10); // Esto agrega un pequeño espacio entre el título y la tabla

// Tabla de detalles de la venta
$pdf->SetFont('helvetica', '', 10);

// Ajuste de celdas para nombre más largo y ajustabilidad
$pdf->Cell(40, 10, 'Producto', 1, 0, 'C');
$pdf->Cell(40, 10, 'Cantidad', 1, 0, 'C');
$pdf->Cell(40, 10, 'Subtotal', 1, 1, 'C');

// Inicializar variable para el total
$total = 0;

// Agregar datos de la venta al PDF
while ($detalle_venta = mysqli_fetch_assoc($resultado)) {
    // Mostrar el nombre del producto, cantidad y subtotal en la tabla
    $pdf->MultiCell(40, 10, htmlspecialchars($detalle_venta['producto_nombre']), 1, 'C', 0, 0, '', '', true);
    $pdf->MultiCell(40, 10, htmlspecialchars($detalle_venta['cantidad']), 1, 'C', 0, 0, '', '', true);
    $pdf->MultiCell(40, 10, '$' . number_format($detalle_venta['subtotal'], 2), 1, 'C', 0, 0, '', '', true);
    
    // Sumar al total
    $total += $detalle_venta['subtotal'];

    // Salto de línea después de cada venta
    $pdf->Ln();
}

// Línea para el total
$pdf->Ln(5);
$pdf->Cell(120, 10, 'Total:', 1, 0, 'R');
$pdf->Cell(40, 10, '$' . number_format($total, 2), 1, 1, 'C');

// Mensaje sobre la sucursal
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'I', 10);
$pdf->Cell(0, 10, 'El producto debe ser retirado y pagado en dos días en la sucursal "La despensa de don rod, Santa Ana Centro".', 0, 1, 'C');

// Mensaje advertencia
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, '**No olvidar descargar comprobante**', 0, 1, 'C');

// Salvar o mostrar el PDF
$pdf->Output('ticket_venta_' . $venta_id . '.pdf', 'I'); // Muestra el PDF directamente en el navegador
exit;
?>
