<?php include('header.php');
include('../conexion/conexion.php')
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

        <!-- Formulario para agregar un producto -->
        <div class="col-md-9 p-4">
            <h2 class="mb-3">Agregar Producto</h2>
            <form action="../controller/c_agregar_producto.php" method="POST" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
                <div class="form-group mb-3">
                    <label for="descripcion">Descripción:</label>
                    <textarea class="form-control" name="descripcion" rows="3" required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="precio">Precio:</label>
                    <input type="number" step="0.01" class="form-control" name="precio" required>
                </div>
                <div class="form-group mb-3">
                    <label for="cantidad_stock">Cantidad en Stock:</label>
                    <input type="number" class="form-control" name="cantidad_stock" required>
                </div>
                <div class="form-group mb-3">
                    <label for="categoria_id">Categoría:</label>
                    <select class="form-control" name="categoria_id" required>
                        <option value="" disabled selected>Seleccione una opción</option>
                        <?php
                        $query = "SELECT categoria_id, nombre_categoria FROM categorias";
                        $resultado = mysqli_query($conn, $query);
                        while ($categoria = mysqli_fetch_assoc($resultado)) {
                            echo "<option value='" . htmlspecialchars($categoria['categoria_id']) . "'>" . htmlspecialchars($categoria['nombre_categoria']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="imagen">Imagen del Producto:</label>
                    <input type="file" class="form-control" name="imagen" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Guardar Producto</button>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>