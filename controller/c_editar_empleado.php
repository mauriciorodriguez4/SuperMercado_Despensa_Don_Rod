<?php
include('../conexion/conexion.php');

// Verifica si el formulario fue enviado por el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibe y valida los datos
    $empleado_id = isset($_POST['empleado_id']) ? intval($_POST['empleado_id']) : 0;
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $puesto = isset($_POST['puesto']) ? trim($_POST['puesto']) : '';
    $salario = isset($_POST['salario']) ? floatval($_POST['salario']) : 0;

    // Verifica que los datos requeridos no estén vacíos
    if ($empleado_id > 0 && !empty($nombre) && !empty($puesto) && $salario > 0) {
        try {
            // Prepara la consulta de actualización
            $query = "UPDATE empleados SET nombre = ?, puesto = ?, salario = ? WHERE empleado_id = ?";
            $stmt = $conn->prepare($query);
            
            // Vincula los valores y ejecuta la consulta
            $stmt->bind_param("ssdi", $nombre, $puesto, $salario, $empleado_id);
            $stmt->execute();

            // Redirige a la página de gestión de empleados
            header("Location: ../vistas/admin_empleados.php");
            exit();
        } catch (Exception $e) {
            // Muestra un mensaje de error si ocurre un problema
            echo "Error al actualizar el empleado: " . $e->getMessage();
        }
    } else {
        echo "Por favor, complete todos los campos obligatorios.";
    }
} else {
    // Redirige al formulario si se intenta acceder al controlador de forma directa
    header("Location: ../vistas/editar_empleado.php");
    exit();
}
?>
