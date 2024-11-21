<?php
include('../conexion/conexion.php');

// Obtener los datos del formulario
$producto_id = $_POST['producto_id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$cantidad_stock = $_POST['cantidad_stock'];
$categoria_id = $_POST['categoria_id'];

// Verificar si se ha subido una nueva imagen
$imagen = $_FILES['imagen']['name'];
$imagen_tmp = $_FILES['imagen']['tmp_name'];

if ($imagen) {
    // Subir nueva imagen
    $imagen_path = '../img/' . basename($imagen);
    move_uploaded_file($imagen_tmp, $imagen_path);

    // Actualizar imagen en la base de datos
    $query = "UPDATE productos SET nombre = '$nombre', descripcion = '$descripcion', precio = '$precio', cantidad_stock = '$cantidad_stock', categoria_id = '$categoria_id', imagen = '$imagen_path' 
    WHERE producto_id = '$producto_id'";
} else {
    // Si no se sube una nueva imagen, no modificar la imagen actual
    $query = "UPDATE productos SET nombre = '$nombre', descripcion = '$descripcion', precio = '$precio', cantidad_stock = '$cantidad_stock', categoria_id = '$categoria_id' WHERE producto_id = '$producto_id'";
}

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    header('Location: ../vistas/admin_productos.php');
} else {
    echo "Error al actualizar el producto: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
