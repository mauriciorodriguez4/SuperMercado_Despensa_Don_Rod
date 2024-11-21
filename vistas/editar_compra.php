<?php
include('header.php');
include('../conexion/conexion.php');

// Obtener el ID del detalle de compra desde la URL
$detalle_compra_id = isset($_GET['detalle_compra_id']) ? $_GET['detalle_compra_id'] : null;

if ($detalle_compra_id) {
    // Obtener los datos del detalle de compra
    $query = "SELECT * FROM detalles_compra WHERE detalle_compra_id = $detalle_compra_id";
    $resultado = mysqli_query($conn, $query);
    $detalle_compra = mysqli_fetch_assoc($resultado);

    if ($detalle_compra) {
        // Obtener el nombre y el precio del producto
        $producto_id = $detalle_compra['producto_id'];
        $producto_query = "SELECT nombre, precio FROM productos WHERE producto_id = $producto_id";
        $producto_result = mysqli_query($conn, $producto_query);
        $producto = mysqli_fetch_assoc($producto_result);
    } else {
        echo "Detalle de compra no encontrado.";
        exit;
    }
} else {
    echo "ID de detalle de compra no proporcionado.";
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar del administrador -->
        <div class="col-md-3 bg-light p-4">
            <h2 class="mb-4">Administración</h2>
            <ul class="nav flex-column">
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
        <div class="col-md-9 mb-4">
            <h2 class="p-4">Editar Detalle de Compra</h2>

            <form action="../controller/c_editar_compra.php" method="POST">
                <input type="hidden" name="detalle_compra_id" value="<?php echo htmlspecialchars($detalle_compra_id); ?>">

                <div class="form-group mb-3">
                    <label for="producto">Producto</label>
                    <input type="text" class="form-control" id="producto" name="producto" value="<?php echo htmlspecialchars($producto['nombre']); ?>" readonly>
                </div>

                <div class="form-group mb-3">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($detalle_compra['cantidad']); ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="subtotal">Subtotal</label>
                    <input type="text" class="form-control" id="subtotal" name="subtotal" value="<?php echo htmlspecialchars($detalle_compra['subtotal']); ?>" readonly required>
                </div>

                <div class="form-group mb-3">
                    <label for="fecha_compra">Fecha de Compra</label>
                    <input type="date" class="form-control" id="fecha_compra" name="fecha_compra" value="<?php echo htmlspecialchars($detalle_compra['fecha_compra']); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>

<script>
// Función para actualizar el subtotal
function actualizarSubtotal() {
    // Obtener el precio unitario del producto
    var precioUnitario = <?php echo $producto['precio']; ?>;

    var cantidad = parseInt(document.getElementById("cantidad").value);

    if (!isNaN(precioUnitario) && !isNaN(cantidad) && cantidad > 0) {
        // Calcular el subtotal
        var subtotal = precioUnitario * cantidad;

        document.getElementById("subtotal").value = subtotal.toFixed(2);
    } else {
        document.getElementById("subtotal").value = "";
    }
}
document.getElementById("cantidad").addEventListener("input", actualizarSubtotal);

// Inicializar el subtotal al cargar la página con los valores actuales
document.addEventListener("DOMContentLoaded", function() {
    actualizarSubtotal();
});
</script>

<?php include('footer.php'); ?>
