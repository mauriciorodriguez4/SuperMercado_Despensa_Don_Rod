<?php
include('../conexion/conexion.php');

// Verificar el estado de orden en la URL y definir el próximo estado
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'none'; // Estado inicial: sin orden
$next_orden = $orden === 'none' ? 'asc' : ($orden === 'asc' ? 'desc' : 'none');

// Crear la consulta SQL según el estado de orden actual
if ($orden === 'asc') {
    $query = "SELECT * FROM productos ORDER BY precio ASC";
} elseif ($orden === 'desc') {
    $query = "SELECT * FROM productos ORDER BY precio DESC";
} else {
    $query = "SELECT * FROM productos"; // Sin orden
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
$title = 'Informe General de Productos';
$title_width = $pdf->GetStringWidth($title) + 6; // Calculamos el ancho del título para centrarlo
$page_width = $pdf->getPageWidth();
$title_x = ($page_width - $title_width) / 2; // Calculamos la posición X para centrar el título

// Colocar el título debajo del logo y centrado en la página
$pdf->SetXY($title_x, 20);
$pdf->Cell(0, 10, $title, 0, 1, 'C');

// Usar Ln() para agregar espacio antes de la tabla
$pdf->Ln(10); // Esto agrega un pequeño espacio entre el título y la tabla

// Tabla de productos
$pdf->SetFont('helvetica', '', 10);

// Ajuste de celdas para nombre más largo y ajustabilidad
$pdf->Cell(50, 10, 'Nombre', 1, 0, 'C');
$pdf->Cell(40, 10, 'Precio', 1, 0, 'C');
$pdf->Cell(40, 10, 'Stock', 1, 0, 'C');
$pdf->Cell(40, 10, 'Fecha', 1, 1, 'C'); // Nueva columna de "Fecha"

// Agregar datos de los productos al PDF
while ($producto = mysqli_fetch_assoc($resultado)) {
    // Mostrar el nombre en varias líneas si es necesario
    $pdf->MultiCell(50, 10, htmlspecialchars($producto['nombre']), 1, 'C', 0, 0, '', '', true);

    // Mostrar el precio y el stock con MultiCell para permitir múltiples líneas
    $pdf->MultiCell(40, 10, '$' . number_format($producto['precio'], 2), 1, 'C', 0, 0, '', '', true);
    $pdf->MultiCell(40, 10, htmlspecialchars($producto['cantidad_stock']), 1, 'C', 0, 0, '', '', true);
    
    // Mostrar la fecha
    $pdf->MultiCell(40, 10, htmlspecialchars($producto['fecha_entrada']), 1, 'C', 0, 1, '', '', true);

    // Salto de línea después de cada producto
    $pdf->Ln();
}

// Salvar o mostrar el PDF
$pdf->Output('reporte_productos.pdf', 'I'); // Muestra el PDF directamente en el navegador
exit;
?>
