<?php
include('../conexion/conexion.php');

// Verificar si se envió el ID de la categoría
if (isset($_GET['categoria_id']) && intval($_GET['categoria_id']) > 0) {
    $categoria_id = intval($_GET['categoria_id']);

    // Consulta para eliminar la categoría
    $query = "DELETE FROM categorias WHERE categoria_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $categoria_id);
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la lista de categorías con un mensaje de éxito
        header("Location: ../vistas/admin_categorias.php?success_delete=1");
    } else {
        // Si no se puede eliminar, mostrar el error
        echo "Error al eliminar la categoría: " . $stmt->error;
    }
} else {
    // Si no se pasa un ID válido, redirigir a la lista de categorías
    header("Location: ../vistas/admin_categorias.php");
    exit();
}
?>
