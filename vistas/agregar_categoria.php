<?php include('header.php'); ?>

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
                    <a class="btn btn-danger w-100" href="controller/c_cerrar_sesion.php">Cerrar sesión</a>
                </li>
            </ul>
        </div>

        <div class="col-md-9 p-4">
            <h2 class="mb-3">Agregar Categoría</h2>
            <form action="../controller/c_agregar_categoria.php" method="POST">
                <div class="form-group mb-3">
                    <label for="nombre_categoria">Nombre de la Categoría:</label>
                    <input type="text" class="form-control" name="nombre_categoria" required>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Categoría</button>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
