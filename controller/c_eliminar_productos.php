<?php
include('../conexion/conexion.php');

// Verificar si se ha recibido el ID del empleado a eliminar
if (isset($_GET['producto_id'])) {
    $producto_id = $_GET['producto_id'];

    // Consulta para eliminar el empleado
    $query = "DELETE FROM productos WHERE producto_id = $producto_id";
    
    if (mysqli_query($conn, $query)) {
        // Redirigir a la página de empleados después de eliminar
        header('Location: ../vistas/admin_productos.php');
    } else {
        // Manejar error en la eliminación
        echo "Error al eliminar el producto: " . mysqli_error($conn);
    }
}
?>
