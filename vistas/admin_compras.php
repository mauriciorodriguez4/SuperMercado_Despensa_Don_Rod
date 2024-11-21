<?php
include('header.php');
include('../conexion/conexion.php');

// Verificar el estado de orden en la URL y definir el próximo estado
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'none'; // Estado inicial: sin orden
$next_orden = $orden === 'none' ? 'asc' : ($orden === 'asc' ? 'desc' : 'none');

if ($orden === 'asc') {
    $query = "SELECT * FROM detalles_compra ORDER BY fecha_compra ASC";
} elseif ($orden === 'desc') {
    $query = "SELECT * FROM detalles_compra ORDER BY fecha_compra DESC";
} else {
    $query = "SELECT * FROM detalles_compra"; // Sin orden
}
$resultado = mysqli_query($conn, $query);
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar del administrador -->
        <div class="col-md-3 bg-light p-4">
            <h2 class="mb-4">Administración</h2>
            <ul class="nav flex-column">
                <li class="nav-item mb-4">
                    <a class="btn btn-secondary w-100" href="../admin.php">Inicio</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="btn btn-success w-100" href="admin_empleados.php">Gestión de Empleados</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="btn btn-success w-100" href="admin_productos.php">Gestión Productos</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="btn btn-success w-100" href="admin_categorias.php">Gestión Categorías</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="btn btn-success w-100" href="admin_compras.php">Detalle de Compras</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="btn btn-danger w-100" href="../controller/c_cerrar_sesion.php">Cerrar sesión</a>
                </li>
            </ul>
        </div>

        <!-- Contenido principal de la página -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="p-4">Detalle de compra</h2>
                <a href="agregar_stock.php" class="btn btn-primary">
                    <i class="bi bi-cart-plus"></i> Agregar stock
                </a>
                <a href="generar_reporte_compra.php" class="btn btn-success">
                    <i class="bi bi-file-earmark-pdf"></i> Generar Reporte PDF
                </a>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Fecha de Compra</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mostrar cada detalle de compra en una fila de la tabla
                    while ($detalle_compra = mysqli_fetch_assoc($resultado)) {                        
                        $producto_id = $detalle_compra['producto_id'];
                        $producto_query = "SELECT nombre FROM productos WHERE producto_id = $producto_id";
                        $producto_result = mysqli_query($conn, $producto_query);
                        $producto = mysqli_fetch_assoc($producto_result);

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($producto['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($detalle_compra['cantidad']) . "</td>";
                        echo "<td>" . htmlspecialchars($detalle_compra['subtotal']) . "</td>";
                        echo "<td>" . htmlspecialchars($detalle_compra['fecha_compra']) . "</td>";

                        echo "<td>
                                <a href='editar_compra.php?detalle_compra_id=" . htmlspecialchars($detalle_compra['detalle_compra_id']) . "' class='btn btn-warning btn-sm'>
                                    <i class='bi bi-pencil-square'></i> Editar
                                </a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que quieres eliminar este detalle de compra?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <a id="confirmDeleteLink" class="btn btn-danger" href="#">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<!-- Script para manejar la lógica de la eliminación -->
<script>
    $('#confirmDeleteModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); 
        var detalleCompraId = button.data('id'); 
        
        var url = '../controller/c_eliminar_detalle_compra.php?detalle_compra_id=' + detalleCompraId;
        $('#confirmDeleteLink').attr('href', url); // Cambia el enlace de eliminación
    });
</script>