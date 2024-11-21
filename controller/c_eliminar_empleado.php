<?php
include('../conexion/conexion.php');

// Verificar si se ha recibido el ID del empleado a eliminar
if (isset($_GET['empleado_id'])) {
    $empleado_id = $_GET['empleado_id'];

    // Consulta para eliminar el empleado
    $query = "DELETE FROM empleados WHERE empleado_id = $empleado_id";
    
    if (mysqli_query($conn, $query)) {
        // Redirigir a la página de empleados después de eliminar
        header('Location: ../vistas/admin_empleados.php');
    } else {
        // Manejar error en la eliminación
        echo "Error al eliminar el empleado: " . mysqli_error($conn);
    }
}
?>
