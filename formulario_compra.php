<?php
session_start();

// Incluir encabezado y conexión a la base de datos
include('header.php');
include('conexion/conexion.php');

$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : "Invitado";
// Consultar los productos del carrito de la base de datos
$sql = "SELECT p.producto_id, p.nombre, p.precio, c.cantidad, (p.precio * c.cantidad) AS subtotal
        FROM carrito c
        JOIN productos p ON c.producto_id = p.producto_id
        WHERE c.usuario_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

// Calcular el total
$total = 0;
?>

<nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
    <div class="container text-light">
        <div class="w-100 d-flex justify-content-between">
            <div>
                <i class="bi bi-envelope-at-fill mx-2"></i>
                <a class="navbar-sm-brand text-light text-decoration-none"
                    href="mailto:info@company.com">info@company.com</a>
                <i class="bi bi-telephone-forward-fill mx-2"></i>
                <a class="navbar-sm-brand text-light text-decoration-none" href="tel:010-020-0340">010-020-0340</a>            
            </div>
            <div>
                <a class="text-light" href="https://www.instagram.com/" target="_blank"><i
                        class="bi bi-instagram"></i></a>
                <a class="text-light" href="https://twitter.com/" target="_blank"><i class="bi bi-twitter-x"></i></a>
                <a class="text-light" href="https://www.linkedin.com/" target="_blank"><i
                        class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Resumen de tu Compra</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar los productos del carrito
            if ($result->num_rows > 0) {
                while ($producto = $result->fetch_assoc()) {
                    $subtotal = $producto['precio'] * $producto['cantidad'];
                    $total += $subtotal;
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($producto['nombre']) . '</td>';
                    echo '<td>$' . number_format($producto['precio'], 2) . '</td>';
                    echo '<td>' . $producto['cantidad'] . '</td>';
                    echo '<td>$' . number_format($subtotal, 2) . '</td>';
                    echo '</tr>';
                    echo '<input type="hidden" name="producto_id[]" value="' . htmlspecialchars($producto['producto_id']) . '">';
                }
            } else {
                echo '<tr><td colspan="4">No hay productos en tu carrito.</td></tr>';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
            </tr>
        </tfoot>
    </table>
    <div class="d-flex justify-content-between">
        <a href="productos.php" class="btn btn-primary">Atras</a>
        <a href="javascript:void(0);" class="btn btn-success" id="finalizarCompraBtn">Finalizar Compra</a>
    </div>
    <br>
</div>

<!-- Modal de confirmación de compra -->
<div class="modal fade" id="confirmCompraModal" tabindex="-1" aria-labelledby="confirmCompraModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmCompraModalLabel">Confirmar compra</h5>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas finalizar tu compra? Esto procesará tu pedido.
            </div>
            <div class="modal-footer">
                <!-- Botón de Cancelar con el atributo `data-dismiss="modal"` para cerrar el modal -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelarCompraBtn">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirmCompraBtnModal">Confirmar Compra</button>
            </div>
        </div>
    </div>
</div>


<?php
// Cerrar la conexión a la base de datos y el archivo footer
$stmt->close();
$conn->close();
include('footer.php');
?>

<script>
    // Abre el modal cuando se hace clic en "Finalizar Compra"
    document.getElementById('finalizarCompraBtn').addEventListener('click', function() {
        $('#confirmCompraModal').modal('show');
    });
    document.getElementById('cancelarCompraBtn').addEventListener('click', function() {
        $('#confirmCompraModal').modal('hide');
    });

    // Al hacer clic en "Confirmar Compra" dentro del modal
    document.getElementById('confirmCompraBtnModal').addEventListener('click', function() {
        // Obtener el usuario_id desde PHP
        let usuarioId =  parseInt(<?php echo htmlspecialchars($usuario_id); ?>);

        fetch('controller/c_finalizar_compra.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                usuario_id: usuarioId
            })
        })
        .then(response => response.json()) // Esperamos una respuesta JSON
        .then(data => {
            if (data.success) {
                // Si la compra fue exitosa, redirigir al usuario
                localStorage.removeItem('carrito');
                localStorage.removeItem('totalCarrito');
                window.location.href = 'compra_realizada.php';  // Redirige a la página de confirmación
            } else {
                // Si hubo un error, mostrar un mensaje
                alert('Error al finalizar la compra: ' + (data.error || 'Desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al intentar finalizar la compra.');
        });

        // Cerrar el modal después de procesar la compra
        $('#confirmCompraModal').modal('hide');
    });
</script>
