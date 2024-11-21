<?php
include('header.php');
include('../conexion/conexion.php');

// Verificar el estado de orden en la URL y definir el próximo estado
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'none';
$next_orden = $orden === 'none' ? 'asc' : ($orden === 'asc' ? 'desc' : 'none');

if ($orden === 'asc') {
    $query = "SELECT * FROM empleados ORDER BY salario ASC";
} elseif ($orden === 'desc') {
    $query = "SELECT * FROM empleados ORDER BY salario DESC";
} else {
    $query = "SELECT * FROM empleados"; // Sin orden
}
$resultado = mysqli_query($conn, $query);
?>

<div class="container-fluid">
    <div class="row">        
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
                <h2 class="p-4">Gestión de Empleados</h2>
                <a href="agregar_empleado.php" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Agregar Empleado
                </a>
                <a href="generar_reporte_empleados.php" class="btn btn-success">
                    <i class="bi bi-file-earmark-pdf"></i> Generar Reporte PDF
                </a>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Puesto</th>
                        <th>
                            <a href="?orden=<?php echo $next_orden; ?>" class="text-black text-decoration-none">Salario
                                <?php
                                if ($orden === 'asc') {
                                    echo '<i class="bi bi-arrow-down"></i>'; // Indicador ascendente
                                } elseif ($orden === 'desc') {
                                    echo '<i class="bi bi-arrow-up"></i>'; // Indicador descendente
                                }
                                ?>
                            </a>
                        </th>
                        <th>Fecha de Contratación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mostrar cada empleado en una fila de la tabla
                    while ($empleado = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($empleado['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($empleado['puesto']) . "</td>";
                        echo "<td>$" . htmlspecialchars(number_format($empleado['salario'], 2)) . "</td>";
                        echo "<td>" . htmlspecialchars($empleado['fecha_contratacion']) . "</td>";
                        echo "<td>
                                <a href='editar_empleado.php?empleado_id=" . htmlspecialchars($empleado['empleado_id']) . "' class='btn btn-warning btn-sm'>
                                    <i class='bi bi-pencil-square'></i> Editar
                                </a>
                                <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#confirmDeleteModal' data-id='" . htmlspecialchars($empleado['empleado_id']) . "'>
                                    <i class='bi bi-trash'></i> Eliminar
                                </button>
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
                ¿Estás seguro de que quieres eliminar este registro?
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
    // Lógica para abrir el modal y configurar el enlace de eliminación
    $('#confirmDeleteModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // El botón que activó el modal
        var empleadoId = button.data('id'); // Obtiene el id del empleado desde el data-id

        // Establecer la URL del enlace de eliminación
        var url = '../controller/c_eliminar_empleado.php?empleado_id=' + empleadoId;
        $('#confirmDeleteLink').attr('href', url); // Cambia el enlace de eliminación
    });
</script>
