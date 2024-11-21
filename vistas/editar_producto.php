<?php 
include('header.php');
include('../conexion/conexion.php');

// Obtener el ID del producto desde la URL
$producto_id = $_GET['producto_id'];

// Obtener los datos del producto
$query = "SELECT * FROM productos WHERE producto_id = '$producto_id'";
$resultado = mysqli_query($conn, $query);

if (mysqli_num_rows($resultado) > 0) {
    $producto = mysqli_fetch_assoc($resultado);
} else {
    echo "Producto no encontrado.";
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

        <!-- Formulario para editar un producto -->
        <div class="col-md-9 p-4">
            <h2 class="mb-3">Editar Producto</h2>
            <form action="../controller/c_editar_producto.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="producto_id" value="<?php echo $producto['producto_id']; ?>">

                <div class="form-group mb-3">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="descripcion">Descripción:</label>
                    <textarea class="form-control" name="descripcion" rows="3" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="precio">Precio:</label>
                    <input type="number" step="0.01" class="form-control" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="cantidad_stock">Cantidad en Stock:</label>
                    <input type="number" class="form-control" name="cantidad_stock" value="<?php echo htmlspecialchars($producto['cantidad_stock']); ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="categoria_id">Categoría:</label>
                    <select class="form-control" name="categoria_id" required>
                        <option value="" disabled>Seleccione una opción</option>
                        <?php
                        $query = "SELECT categoria_id, nombre_categoria FROM categorias";
                        $resultado = mysqli_query($conn, $query);
                        while ($categoria = mysqli_fetch_assoc($resultado)) {
                            $selected = ($producto['categoria_id'] == $categoria['categoria_id']) ? "selected" : "";
                            echo "<option value='" . htmlspecialchars($categoria['categoria_id']) . "' $selected>" . htmlspecialchars($categoria['nombre_categoria']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="imagen">Imagen del Producto:</label>
                    <input type="file" class="form-control" name="imagen" accept="image/*">
                    <small>Imagen actual: <?php echo htmlspecialchars($producto['imagen']); ?></small>
                </div>

                <button type="submit" class="btn btn-warning">Actualizar Producto</button>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
