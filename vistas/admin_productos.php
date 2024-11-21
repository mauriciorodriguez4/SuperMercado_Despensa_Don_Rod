<?php
include('header.php');
include('../conexion/conexion.php');

// Definir cuántos registros mostrar por página
$registros_por_pagina = 8;

// Obtener el número de página actual desde la URL (si no existe, se establece en 1)
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pagina = $pagina < 1 ? 1 : $pagina; // Asegurarse de que la página sea al menos 1

// Calcular el índice de inicio para la consulta LIMIT
$inicio = ($pagina - 1) * $registros_por_pagina;

// Filtrar por categoría (si se selecciona)
$categoria_filtro = isset($_GET['categoria']) ? mysqli_real_escape_string($conn, $_GET['categoria']) : '';

// Verificar el estado de orden en la URL y definir el próximo estado
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'none'; // Estado inicial: sin orden
$next_orden = $orden === 'none' ? 'asc' : ($orden === 'asc' ? 'desc' : 'none');

// Crear la consulta SQL según el estado de orden actual y el filtro de categoría
$query = "SELECT * FROM productos WHERE categoria_id LIKE '%$categoria_filtro%'";

// Ordenar si es necesario
if ($orden === 'asc') {
    $query .= " ORDER BY precio ASC";
} elseif ($orden === 'desc') {
    $query .= " ORDER BY precio DESC";
}

// Añadir el LIMIT para la paginación
$query .= " LIMIT $inicio, $registros_por_pagina";

// Ejecutar la consulta
$resultado = mysqli_query($conn, $query);

// Contar el total de registros en la tabla productos con el filtro aplicado
$query_total = "SELECT COUNT(*) AS total FROM productos WHERE categoria_id LIKE '%$categoria_filtro%'";

$resultado_total = mysqli_query($conn, $query_total);
$total_registros = mysqli_fetch_assoc($resultado_total)['total'];

// Calcular el número total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener categorías para el filtro
$query_categorias = "SELECT * FROM categorias";
$resultado_categorias = mysqli_query($conn, $query_categorias);

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
                    <a class="btn btn-success w-100" href="admin_productos.php">Gestión de Productos</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="btn btn-success w-100" href="admin_categorias.php">Gestión de Categorías</a>
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
            <br>
            <div class="d-flex justify-content-between mb-3">
                <h2 class="p-1">Gestión de Productos</h2>
                <!-- Botón para agregar producto -->
                <a href="agregar_productos.php" class="btn btn-success mb-2"><i class="bi bi-person-plus"></i> Agregar Producto</a>

                <!-- Botón para generar PDF -->
                <a href="generar_reporte_productos.php" class="btn btn-primary mb-2"><i class="bi bi-file-earmark-pdf"></i> Generar PDF</a>

                <!-- Filtro de Categoría y botón de filtro -->
                <form method="GET" class="d-flex mb-2">
                    <select name="categoria" class="form-control mr-2">
                        <option value="">Todos</option>
                        <?php
                        while ($categoria = mysqli_fetch_assoc($resultado_categorias)) {
                            echo "<option value='" . htmlspecialchars($categoria['categoria_id']) . "' " . ($categoria['categoria_id'] == $categoria_filtro ? 'selected' : '') . ">" . htmlspecialchars($categoria['nombre_categoria']) . "</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>
                            <a href="?orden=<?php echo $next_orden; ?>&categoria=<?php echo htmlspecialchars($categoria_filtro); ?>" class="text-black text-decoration-none">Precio
                                <?php
                                if ($orden === 'asc') {
                                    echo '<i class="bi bi-arrow-down"></i>';
                                } elseif ($orden === 'desc') {
                                    echo '<i class="bi bi-arrow-up"></i>';
                                }
                                ?>
                            </a>
                        </th>
                        <th>Stock</th>
                        <th>Fecha</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mostrar cada producto en una fila de la tabla
                    while ($producto = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($producto['nombre']) . "</td>";
                        echo "<td>$" . htmlspecialchars(number_format($producto['precio'], 2)) . "</td>";
                        echo "<td>" . htmlspecialchars($producto['cantidad_stock']) . "</td>";
                        echo "<td>" . htmlspecialchars($producto['fecha_entrada']) . "</td>";
                        echo "<td><img src='../img/" . htmlspecialchars($producto['imagen']) . "' alt='Imagen del producto' style='width: 50px; height: 50px;'></td>";

                        echo "<td>
                                <a href='editar_producto.php?producto_id=" . htmlspecialchars($producto['producto_id']) . "' class='btn btn-warning btn-sm'>
                                    <i class='bi bi-pencil-square'></i> Editar
                                </a>
                                <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#confirmDeleteModal' data-id='" . htmlspecialchars($producto['producto_id']) . "'>
                                    <i class='bi bi-trash'></i> Eliminar
                                </button>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php
                    // Enlaces de paginación
                    for ($i = 1; $i <= $total_paginas; $i++) {
                        echo "<li class='page-item" . ($i == $pagina ? " active" : "") . "'>
                                <a class='page-link' href='admin_productos.php?pagina=$i&orden=$orden&categoria=$categoria_filtro'>$i</a>
                              </li>";
                    }
                    ?>
                </ul>
            </nav>
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
                ¿Estás seguro de que deseas eliminar este producto?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <a href="#" id="confirmDeleteLink" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>


<?php
include('footer.php');
?>

<script>
    // Pasar el ID del producto al enlace de eliminación
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var productId = button.data('id'); // Obtener el ID del producto
        var modal = $(this);
        modal.find('#confirmDeleteLink').attr('href', '../controller/c_eliminar_productos.php?producto_id=' + productId);
    });
</script>