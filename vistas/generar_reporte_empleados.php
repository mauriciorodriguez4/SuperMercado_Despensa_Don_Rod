<?php
include('../conexion/conexion.php');

// Verificar el estado de orden en la URL y definir el próximo estado
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'none'; // Estado inicial: sin orden
$next_orden = $orden === 'none' ? 'asc' : ($orden === 'asc' ? 'desc' : 'none');

// Crear la consulta SQL según el estado de orden actual
if ($orden === 'asc') {
    $query = "SELECT * FROM empleados ORDER BY salario ASC";
} elseif ($orden === 'desc') {
    $query = "SELECT * FROM empleados ORDER BY salario DESC";
} else {
    $query = "SELECT * FROM empleados"; // Sin orden
}

$resultado = mysqli_query($conn, $query);

// Incluir la librería TCPDF
require_once('../tcpdf/tcpdf.php');

// Crear un nuevo PDF
$pdf = new TCPDF();
$pdf->AddPage();

// Agregar el logo (asegúrate de que la ruta sea correcta)
$logo_path = '../img/logo.jpg'; // Cambia la ruta al lugar donde tengas el logo
$pdf->Image($logo_path, 10, 10, 30, 0, 'JPG'); // Logo en la esquina superior izquierda

// Establecer la fuente para el título
$pdf->SetFont('helvetica', '', 16);

// Calcular la posición para el título centrado en la página
$title = 'Informe General de Empleados';
$title_width = $pdf->GetStringWidth($title) + 6; // Calculamos el ancho del título para centrarlo
$page_width = $pdf->getPageWidth();
$title_x = ($page_width - $title_width) / 2; // Calculamos la posición X para centrar el título

// Colocar el título debajo del logo y centrado en la página
$pdf->SetXY($title_x, 20); // Establecemos la posición para el título, debajo del logo
$pdf->Cell(0, 10, $title, 0, 1, 'C');

// Usar Ln() para agregar espacio antes de la tabla
$pdf->Ln(10); // Esto agrega un pequeño espacio entre el título y la tabla

// Tabla de empleados
$pdf->SetFont('helvetica', '', 10);

// Centrar las celdas de la tabla
$pdf->Cell(40, 10, 'Nombre', 1, 0, 'C');
$pdf->Cell(40, 10, 'Puesto', 1, 0, 'C');
$pdf->Cell(40, 10, 'Salario', 1, 0, 'C');
$pdf->Cell(40, 10, 'Fecha Contratacion', 1, 1, 'C');

// Agregar datos de los empleados al PDF
while ($empleado = mysqli_fetch_assoc($resultado)) {
    $pdf->Cell(40, 10, htmlspecialchars($empleado['nombre']), 1, 0, 'C');
    $pdf->Cell(40, 10, htmlspecialchars($empleado['puesto']), 1, 0, 'C');
    $pdf->Cell(40, 10, '$' . number_format($empleado['salario'], 2), 1, 0, 'C');
    $pdf->Cell(40, 10, htmlspecialchars($empleado['fecha_contratacion']), 1, 1, 'C');
}

// Salvar o mostrar el PDF
$pdf->Output('reporte_empleados.pdf', 'I'); // Muestra el PDF directamente en el navegador
exit;
?>
