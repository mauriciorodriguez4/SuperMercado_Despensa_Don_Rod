<?php
include('../conexion/conexion.php');
header('Content-Type: application/json');

try {
    // Leer los datos enviados por el cliente
    $input = json_decode(file_get_contents('php://input'), true);

    // Validar que se recibieron los datos necesarios
    if (isset($input['usuario_id'], $input['producto_id'])) {
        $usuario_id = intval($input['usuario_id']);
        $producto_id = intval($input['producto_id']);

        // Consulta para eliminar el producto del carrito
        $query = "DELETE FROM carrito WHERE usuario_id = ? AND producto_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $usuario_id, $producto_id);

        if ($stmt->execute()) {
            // Respuesta en caso de éxito
            echo json_encode(['success' => true]);
        } else {
            // Respuesta en caso de error en la ejecución
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        // Cerrar el statement
        $stmt->close();
    } else {
        // Respuesta en caso de datos incompletos
        echo json_encode(['success' => false, 'error' => 'Datos incompletos: usuario_id o producto_id faltantes.']);
    }
} catch (Exception $e) {
    // Respuesta en caso de una excepción
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
