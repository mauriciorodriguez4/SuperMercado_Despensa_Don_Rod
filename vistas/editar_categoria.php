<?php
include('header.php');
include('../conexion/conexion.php');

// Obtén el ID de la categoría desde la URL
$categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;

// Si no hay un ID válido, redirige a la lista de categorías
if ($categoria_id <= 0) {
    echo "<p>ID inválido. Redirigiendo...</p>";
    header("Location: admin_categorias.php");
    exit();
}

// Consulta para obtener los datos de la categoría
$query = "SELECT * FROM categorias WHERE categoria_id = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param("i", $categoria_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

// Si la categoría no existe, redirige a la lista de categorías
if ($result->num_rows === 0) {
    echo "<p>Categoría no encontrada. Redirigiendo...</p>";
    header("Location: admin_categorias.php");
    exit();
}

// Obtén los datos de la categoría
$categoria = $result->fetch_assoc();
?>

<div class="container">
    <h2 class="mb-3">Editar Categoría</h2>
    <form action="../controller/c_editar_categoria.php" method="POST">
        <input type="hidden" name="categoria_id" value="<?php echo htmlspecialchars($categoria['categoria_id']); ?>">
        
        <div class="form-group mb-3">
            <label for="nombre_categoria">Nombre de la Categoría:</label>
            <input type="text" class="form-control" name="nombre_categoria" value="<?php echo htmlspecialchars($categoria['nombre_categoria']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="admin_categorias.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include('footer.php'); ?>
