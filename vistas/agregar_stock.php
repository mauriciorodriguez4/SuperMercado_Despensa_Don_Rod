<?php
include('header.php');
include('../conexion/conexion.php');

// Consulta para obtener todos los productos
$query = "SELECT * FROM productos"; // Ajusta la consulta si es necesario según tu estructura de base de datos
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error al ejecutar la consulta: " . mysqli_error($conn));
}
?>

<div class="container mb-4">
    <h2 class="mb-3">Agregar Stock</h2>
    <form action="../controller/c_agregar_compra.php" method="POST">
        <div class="form-group mb-3">
            <label for="producto_id">Producto:</label>
            <select class="form-control" name="producto_id" id="producto_id" required>
                <option value="" disabled selected>Seleccionar Producto</option>
                <?php
                // Mostrar los productos disponibles con el precio en un atributo data-precio
                while ($producto = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . htmlspecialchars($producto['producto_id']) . "' data-precio='" . htmlspecialchars($producto['precio']) . "'>" . htmlspecialchars($producto['nombre']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="cantidad">Cantidad:</label>
            <input type="number" class="form-control" name="cantidad" id="cantidad" min="1" required>
        </div>

        <div class="form-group mb-3">
            <label for="subtotal">Subtotal:</label>
            <input type="number" class="form-control" name="subtotal" id="subtotal" step="0.01" min="0" readonly required>
        </div>

        <div class="form-group mb-3">
            <label for="fecha_compra">Fecha de Compra:</label>
            <input type="date" class="form-control" name="fecha_compra" required>
        </div>

        <button type="submit" class="btn btn-primary">Agregar Stock</button>
        <a href="admin_compras.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
// Función para actualizar el subtotal
function actualizarSubtotal() {
    // Obtener el producto seleccionado
    var productoSelect = document.getElementById("producto_id");
    var producto = productoSelect.options[productoSelect.selectedIndex];

    // Obtener el precio unitario del producto seleccionado
    var precioUnitario = parseFloat(producto.getAttribute("data-precio"));

    // Obtener la cantidad ingresada
    var cantidad = parseInt(document.getElementById("cantidad").value);

    // Verificar si el precio y la cantidad son números válidos
    if (!isNaN(precioUnitario) && !isNaN(cantidad) && cantidad > 0) {
        // Calcular el subtotal
        var subtotal = precioUnitario * cantidad;

        // Mostrar el subtotal en el campo correspondiente
        document.getElementById("subtotal").value = subtotal.toFixed(2);
    } else {
        // Si no son válidos, limpiar el campo de subtotal
        document.getElementById("subtotal").value = "";
    }
}

// Añadir eventos para actualizar el subtotal cuando cambien la cantidad o el producto
document.getElementById("producto_id").addEventListener("change", actualizarSubtotal);
document.getElementById("cantidad").addEventListener("input", actualizarSubtotal);
</script>

<?php include('footer.php'); ?>
