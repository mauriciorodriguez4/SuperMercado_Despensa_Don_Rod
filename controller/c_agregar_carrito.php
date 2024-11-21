<?php
include('../conexion/conexion.php');
header('Content-Type: application/json');

try {
    // Leer los datos enviados por AJAX
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id'], $input['precio'], $input['cantidad'], $input['usuario_id'])) {
        $usuario_id = intval($input['usuario_id']); // ID del usuario, ajusta según tu lógica de autenticación
        $producto_id = intval($input['id']);
        $cantidad = intval($input['cantidad']);
        $precio = floatval($input['precio']);

        // Consulta para insertar en el carrito
        $sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad, subtotal)
                VALUES (?, ?, ?, ? * (SELECT precio FROM productos WHERE producto_id = ?))";

        $stmt = $conn->prepare($sql);
        $subtotal = $cantidad * $precio;
        $stmt->bind_param('iiidi', $usuario_id, $producto_id, $cantidad, $cantidad, $producto_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
