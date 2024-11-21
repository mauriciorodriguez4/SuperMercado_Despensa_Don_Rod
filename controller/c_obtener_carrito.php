<?php
// Incluir la conexión a la base de datos
include('../conexion/conexion.php');

// Configurar la cabecera para devolver JSON
header('Content-Type: application/json');

try {
    // Leer los datos enviados por AJAX
    $input = json_decode(file_get_contents('php://input'), true);

    // Verificar que el ID del usuario esté presente
    if (isset($input['usuario_id'])) {
        $usuario_id = intval($input['usuario_id']); // ID del usuario

        // Consulta para obtener los productos en el carrito del usuario
        $sql = "SELECT 
                    c.id AS carrito_id, 
                    p.id AS producto_id, 
                    p.nombre, 
                    p.precio, 
                    c.cantidad, 
                    (c.cantidad * p.precio) AS subtotal, 
                    p.imagen
                FROM carrito c
                JOIN productos p ON c.producto_id = p.id
                WHERE c.usuario_id = ?";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $usuario_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $carrito = [];

            // Construir el arreglo de productos en el carrito
            while ($row = $result->fetch_assoc()) {
                $carrito[] = [
                    'carrito_id' => $row['carrito_id'],
                    'producto_id' => $row['producto_id'],
                    'nombre' => $row['nombre'],
                    'precio' => $row['precio'],
                    'cantidad' => $row['cantidad'],
                    'subtotal' => $row['subtotal'],
                    'imagen' => $row['imagen']
                ];
            }

            // Verificar si el carrito tiene productos
            if (count($carrito) > 0) {
                echo json_encode(['success' => true, 'carrito' => $carrito]);
            } else {
                echo json_encode(['success' => false, 'message' => 'El carrito está vacío.']);
            }
        } else {
            // Error al ejecutar la consulta
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        // Cerrar el statement
        $stmt->close();
    } else {
        // Respuesta si faltan datos en la solicitud
        echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
    }
} catch (Exception $e) {
    // Manejo de errores en caso de excepciones
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
