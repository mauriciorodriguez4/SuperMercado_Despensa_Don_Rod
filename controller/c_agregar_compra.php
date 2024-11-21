<?php
include('../conexion/conexion.php');

// Verificar si los datos del formulario fueron enviados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $producto_id = isset($_POST['producto_id']) ? intval($_POST['producto_id']) : 0;
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;
    $subtotal = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0;
    $fecha_compra = isset($_POST['fecha_compra']) ? $_POST['fecha_compra'] : '';

    // Verificar que los datos sean válidos
    if ($producto_id <= 0 || $cantidad <= 0 || $subtotal <= 0 || empty($fecha_compra)) {
        die("Datos inválidos. Por favor, revisa los campos.");
    }

    // Consultar el precio unitario del producto seleccionado
    $query = "SELECT * FROM productos WHERE producto_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die("Producto no encontrado.");
    }

    // Obtener el producto y su precio
    $producto = $result->fetch_assoc();
    $precio_unitario = $producto['precio'];
    $stock_actual = $producto['cantidad_stock'];

    // Insertar la compra en la tabla detalles_compra
    $query_insert = "INSERT INTO detalles_compra (producto_id, cantidad, subtotal, fecha_compra) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($query_insert);
    if (!$stmt_insert) {
        die("Error al preparar la consulta de inserción: " . $conn->error);
    }

    $stmt_insert->bind_param("iids", $producto_id, $cantidad, $subtotal, $fecha_compra);
    $stmt_insert->execute();

    if ($stmt_insert->affected_rows === 0) {
        die("Error al registrar la compra.");
    }

    // **Actualizar el stock del producto sumando la cantidad comprada**
    $nuevo_stock = $stock_actual + $cantidad; // Cambiado de "restar" a "sumar"
    $query_update = "UPDATE productos SET cantidad_stock = ? WHERE producto_id = ?";
    $stmt_update = $conn->prepare($query_update);
    if (!$stmt_update) {
        die("Error al preparar la consulta de actualización del stock: " . $conn->error);
    }

    $stmt_update->bind_param("ii", $nuevo_stock, $producto_id);
    $stmt_update->execute();

    if ($stmt_update->affected_rows === 0) {
        die("Error al actualizar el stock.");
    }

    // Redirigir al usuario a la lista de compras (o donde sea necesario)
    header("Location: ../vistas/admin_compras.php?mensaje=Stock actualizado correctamente.");
    exit();
}
?>
