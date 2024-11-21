<?php
include('../conexion/conexion.php');

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $categoria_id = isset($_POST['categoria_id']) ? intval($_POST['categoria_id']) : 0;
    $nombre_categoria = mysqli_real_escape_string($conn, $_POST['nombre_categoria']);

    // Validar que el nombre de la categoría no esté vacío
    if (!empty($nombre_categoria) && $categoria_id > 0) {
        // Consulta para actualizar la categoría
        $query = "UPDATE categorias SET nombre_categoria = ? WHERE categoria_id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("si", $nombre_categoria, $categoria_id);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a la página de gestión de categorías con mensaje de éxito
            header("Location: ../vistas/admin_categorias.php?success=1");
        } else {
            echo "Error al actualizar la categoría: " . $stmt->error;
        }
    } else {
        echo "Por favor, ingresa un nombre para la categoría.";
    }
}
?>
