<?php
include('header.php');
include('../conexion/conexion.php');



// Obtén el ID del empleado desde la URL
$empleado_id = isset($_GET['empleado_id']) ? intval($_GET['empleado_id']) : 0;


// Si no hay un ID válido, redirige a la lista de empleados
if ($empleado_id <= 0) {
    echo "<p>ID inválido. Redirigiendo...</p>";
    header("Location: admin_empleados.php");
    exit();
}

// Consulta para obtener los datos del empleado
$query = "SELECT * FROM empleados WHERE empleado_id = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param("i", $empleado_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

// Si el empleado no existe, redirige a la lista de empleados
if ($result->num_rows === 0) {
    echo "<p>Empleado no encontrado. Redirigiendo...</p>";
    header("Location: admin_empleados.php");
    exit();
}

// Obtén los datos del empleado
$empleado = $result->fetch_assoc();
?>

<div class="container">
    <h2 class="mb-3">Editar Empleado</h2>
    <form action="../controller/c_editar_empleado.php" method="POST">
        <input type="hidden" name="empleado_id" value="<?php echo htmlspecialchars($empleado['empleado_id']); ?>">
        
        <div class="form-group mb-3">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($empleado['nombre']); ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="puesto">Puesto:</label>
            <input type="text" class="form-control" name="puesto" value="<?php echo htmlspecialchars($empleado['puesto']); ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="salario">Salario:</label>
            <input type="number" step="0.01" class="form-control" name="salario" value="<?php echo htmlspecialchars($empleado['salario']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="gestion_empleados.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include('footer.php'); ?>
