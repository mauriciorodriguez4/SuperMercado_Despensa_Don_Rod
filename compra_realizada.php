<?php
session_start();

// Incluir encabezado y conexión a la base de datos
include('header.php');
include('conexion/conexion.php');

// Obtener el usuario_id de la sesión
$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;



// Verificar si el usuario tiene un ID válido
if ($usuario_id) {
    // Consultar el último pedido del usuario
    $sql = "SELECT v.venta_id, v.total_venta, v.fecha_venta
            FROM ventas v
            WHERE v.usuario_id = ?
            ORDER BY v.fecha_venta DESC
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $venta = $result->fetch_assoc();
        $venta_id = $venta['venta_id'];
        $total_venta = $venta['total_venta'];
        $fecha_venta = $venta['fecha_venta'];
    } else {
        echo "<p>No se encontró la compra.</p>";
        exit;
    }

    $sql_detalle = "SELECT p.nombre, dv.cantidad, dv.subtotal
    FROM detalle_ventas dv
    JOIN productos p ON dv.producto_id = p.producto_id
    WHERE dv.venta_id = ?";

    $stmt_detalle = $conn->prepare($sql_detalle);
    $stmt_detalle->bind_param('i', $venta_id);
    $stmt_detalle->execute();
    $result_detalle = $stmt_detalle->get_result();
} else {
    echo "<p>Sesión inválida. Por favor, inicia sesión nuevamente.</p>";
    exit;
}

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();


?>
<nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
    <div class="container text-light">
        <div class="w-100 d-flex justify-content-between">
            <div>
                <i class="bi bi-envelope-at-fill mx-2"></i>
                <a class="navbar-sm-brand text-light text-decoration-none"
                    href="mailto:info@company.com">info@company.com</a>
                <i class="bi bi-telephone-forward-fill mx-2"></i>
                <a class="navbar-sm-brand text-light text-decoration-none" href="tel:010-020-0340">010-020-0340</a>            
            </div>
            <div>
                <a class="text-light" href="https://www.instagram.com/" target="_blank"><i
                        class="bi bi-instagram"></i></a>
                <a class="text-light" href="https://twitter.com/" target="_blank"><i class="bi bi-twitter-x"></i></a>
                <a class="text-light" href="https://www.linkedin.com/" target="_blank"><i
                        class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h2>Compra Realizada con Éxito</h2>
    <p>¡Gracias por tu compra!</p>

    <h4>Detalles de la compra:</h4>
    <ul>
        <li><strong>ID de la Venta:</strong> <?php echo $venta_id; ?></li>
        <li><strong>Total:</strong> $<?php echo number_format($total_venta, 2); ?></li>
        <li><strong>Fecha de la Compra:</strong> <?php echo date("d/m/Y H:i:s", strtotime($fecha_venta)); ?></li>
    </ul>

    <br>
    <a href="index.php" class="btn btn-primary">Volver al Inicio</a>
    <a href="generar_pdf_venta.php?venta_id=<?php echo $venta_id; ?>" class="btn btn-success">Descargar Comprobante</a>
    <br>
</div>
<br>
<?php include('footer.php'); ?>