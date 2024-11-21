<?php
include('../conexion/conexion.php');
header('Content-Type: application/json');

try {
    // Leer los datos enviados por AJAX
    $input = json_decode(file_get_contents('php://input'), true);

    // Verificar que el usuario_id está presente
    if (isset($input['usuario_id'])) {
        $usuario_id = intval($input['usuario_id']); // ID del usuario

        // Paso 1: Consultar los productos del carrito para este usuario, incluyendo el precio desde la tabla productos
        $sql = "
            SELECT c.producto_id, c.cantidad, p.precio, (p.precio * c.cantidad) AS subtotal
            FROM carrito c
            JOIN productos p ON c.producto_id = p.producto_id
            WHERE c.usuario_id = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        $total_venta = 0;

        while ($producto = $result->fetch_assoc()) {
            $productos[] = $producto;
            $total_venta += $producto['subtotal'];  // Sumar los subtotales para el total de la venta
        }

        // Si no hay productos, devolver error
        if (empty($productos)) {
            echo json_encode(['success' => false, 'error' => 'Carrito vacío']);
            exit;
        }

        // Paso 2: Insertar en la tabla ventas
        $query_ventas = "INSERT INTO ventas (usuario_id, total_venta) VALUES (?, ?)";
        $stmt1 = $conn->prepare($query_ventas);
        $stmt1->bind_param('id', $usuario_id, $total_venta);
        if (!$stmt1->execute()) {
            echo json_encode(['success' => false, 'error' => $stmt1->error]);
            exit;
        }

        // Obtener el ID de la venta recién creada
        $venta_id = $conn->insert_id;

        // Paso 3: Insertar detalles de la venta en la tabla detalle_ventas
        $query_detalle_ventas = "INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, subtotal) VALUES (?, ?, ?, ?)";
        $stmt2 = $conn->prepare($query_detalle_ventas);

        foreach ($productos as $producto) {
            $stmt2->bind_param(
                'iiid',
                $venta_id,
                $producto['producto_id'],
                $producto['cantidad'],
                $producto['subtotal']  // Insertar el subtotal calculado
            );
            if (!$stmt2->execute()) {
                echo json_encode(['success' => false, 'error' => $stmt2->error]);
                exit;
            }
        }

        // Paso 4: Actualizar el stock de cada producto
        $query_actualizar_stock = "UPDATE productos SET cantidad_stock = cantidad_stock - ? WHERE producto_id = ?";
        $stmt3 = $conn->prepare($query_actualizar_stock);

        foreach ($productos as $producto) {
            $stmt3->bind_param(
                'ii',
                $producto['cantidad'], // Reducir la cantidad en stock
                $producto['producto_id']
            );
            if (!$stmt3->execute()) {
                echo json_encode(['success' => false, 'error' => $stmt3->error]);
                exit;
            }
        }

        // Paso 5: Eliminar productos del carrito
        $query_limpiar_carrito = "DELETE FROM carrito WHERE usuario_id = ?";
        $stmt4 = $conn->prepare($query_limpiar_carrito);
        $stmt4->bind_param('i', $usuario_id);
        if (!$stmt4->execute()) {
            echo json_encode(['success' => false, 'error' => $stmt4->error]);
            exit;
        }

        // Si todo fue exitoso, devolver éxito
        echo json_encode(['success' => true]);

    } else {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// Cerrar la conexión
$conn->close();
?>
