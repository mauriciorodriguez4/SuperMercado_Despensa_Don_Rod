<?php
include('../conexion/conexion.php');

// Verificar si se recibió el formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los valores del formulario
    $detalle_compra_id = isset($_POST['detalle_compra_id']) ? $_POST['detalle_compra_id'] : null;
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : null;
    $subtotal = isset($_POST['subtotal']) ? $_POST['subtotal'] : null;
    $fecha_compra = isset($_POST['fecha_compra']) ? $_POST['fecha_compra'] : null;

    // Validar que los campos no estén vacíos
    if ($detalle_compra_id && $cantidad && $subtotal && $fecha_compra) {
        $query_producto_id = "SELECT producto_id FROM detalles_compra WHERE detalle_compra_id = ?";
        
        if ($stmt = mysqli_prepare($conn, $query_producto_id)) {
            mysqli_stmt_bind_param($stmt, "i", $detalle_compra_id);        
            mysqli_stmt_execute($stmt);
            
            $resultado = mysqli_stmt_get_result($stmt);
            $producto = mysqli_fetch_assoc($resultado);
            $producto_id = $producto['producto_id'];

            $query_actualizar_detalle = "UPDATE detalles_compra 
                                        SET cantidad = ?, subtotal = ?, fecha_compra = ? 
                                        WHERE detalle_compra_id = ?";
            if ($stmt_actualizar = mysqli_prepare($conn, $query_actualizar_detalle)) {                
                mysqli_stmt_bind_param($stmt_actualizar, "dssi", $cantidad, $subtotal, $fecha_compra, $detalle_compra_id);
                
                if (mysqli_stmt_execute($stmt_actualizar)) {                    
                    $query_actualizar_stock = "UPDATE productos 
                                               SET cantidad_stock = cantidad_stock + ? 
                                               WHERE producto_id = ?";
                    if ($stmt_stock = mysqli_prepare($conn, $query_actualizar_stock)) {
                        // Asociar los parámetros (sumamos la cantidad al stock)
                        mysqli_stmt_bind_param($stmt_stock, "di", $cantidad, $producto_id);
                        
                        // Ejecutar la consulta
                        if (mysqli_stmt_execute($stmt_stock)) {
                            // Redirigir al listado de compras con éxito
                            header('Location: ../vistas/admin_compras.php?success=detalle_actualizado');
                            exit;
                        } else {
                            echo "Error al actualizar el stock del producto: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Error al preparar la consulta de actualización de stock: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error al actualizar el detalle de compra: " . mysqli_error($conn);
                }

                mysqli_stmt_close($stmt_actualizar);
            } else {
                echo "Error al preparar la consulta de actualización del detalle: " . mysqli_error($conn);
            }
        } else {
            echo "Error al obtener el producto_id del detalle de compra: " . mysqli_error($conn);
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
} else {
    header('Location: ../vistas/admin_compras.php');
    exit;
}
?>
