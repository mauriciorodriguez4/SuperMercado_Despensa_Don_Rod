<?php
include('../conexion/conexion.php');

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad_stock = $_POST['cantidad_stock'];
    $categoria_id = $_POST['categoria_id'];

    // Verificar si se ha subido una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        // Obtener información de la imagen subida
        $imagen = $_FILES['imagen'];
        $nombre_imagen = $imagen['name'];
        $ruta_temporal = $imagen['tmp_name'];
        $ruta_destino = "../img/" . $nombre_imagen;

        // Mover la imagen a la carpeta "img"
        if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
            // Si la imagen se movió correctamente, guardar la ruta en la base de datos
            $imagen_path = "../img/" . $nombre_imagen; // Ruta relativa a la carpeta 'img'

            // Consulta para insertar el producto en la base de datos
            $query = "INSERT INTO productos (nombre, descripcion, precio, cantidad_stock, categoria_id, imagen) 
                      VALUES ('$nombre', '$descripcion', '$precio', '$cantidad_stock', '$categoria_id', '$imagen_path')";

            if (mysqli_query($conn, $query)) {
                // Redirigir o mostrar un mensaje de éxito
                header("Location: ../vistas/admin_productos.php?success=1");
                exit;
            } else {
                echo "Error al insertar el producto: " . mysqli_error($conn);
            }
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        echo "No se ha subido ninguna imagen o ha ocurrido un error.";
    }
}
?>
