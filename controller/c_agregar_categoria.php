<?php
include('../conexion/conexion.php');

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre_categoria = mysqli_real_escape_string($conn, $_POST['nombre_categoria']);

    // Validar que el campo no esté vacío
    if (!empty($nombre_categoria)) {
        // Preparar la consulta SQL para insertar la categoría
        $query = "INSERT INTO categorias (nombre_categoria) VALUES ('$nombre_categoria')";
        
        // Ejecutar la consulta
        if (mysqli_query($conn, $query)) {
            // Redirigir a la página de gestión de categorías si la inserción fue exitosa
            header("Location: ../vistas/admin_categorias.php?success=1");
        } else {
            // Si hay un error en la inserción
            echo "Error al agregar la categoría: " . mysqli_error($conn);
        }
    } else {
        // Si el campo nombre_categoria está vacío
        echo "Por favor, ingresa un nombre para la categoría.";
    }
}
?>
