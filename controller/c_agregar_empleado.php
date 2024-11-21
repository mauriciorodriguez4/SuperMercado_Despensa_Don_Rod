<?php
// Incluye el archivo de conexión a la base de datos
include('../conexion/conexion.php');

// Verifica si los datos han sido enviados mediante el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibe y valida los datos del formulario
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $puesto = isset($_POST['puesto']) ? trim($_POST['puesto']) : '';
    $salario = isset($_POST['salario']) ? floatval($_POST['salario']) : 0;

    // Valida que los campos requeridos no estén vacíos
    if (!empty($nombre) && !empty($puesto) && $salario > 0) {
        try {
            // Prepara la consulta de inserción
            $query = "INSERT INTO empleados (nombre, puesto, salario, fecha_contratacion) 
                      VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
            $stmt = $conn->prepare($query);
            
            // Vincula los valores y ejecuta la consulta
            $stmt->bind_param("ssd", $nombre, $puesto, $salario);
            $stmt->execute();

            // Redirige a la página de gestión de empleados si la inserción fue exitosa
            header("Location: ../vistas/admin_empleados.php");
            exit();
        } catch (Exception $e) {
            // Muestra un mensaje de error si ocurrió un problema
            echo "Error al insertar el empleado: " . $e->getMessage();
        }
    } else {
        echo "Por favor, complete todos los campos obligatorios.";
    }
} else {
    // Redirige al formulario si se intenta acceder al controlador de forma directa
    header("Location: agregar_empleado.php");
    exit();
}
?>
