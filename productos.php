<?php include('header.php'); ?>
<?php
session_start();
$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : "Invitado";
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

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="index.php">
            <img src="img/logo.png" alt="Logo" width="120" height="auto">
        </a>
        <div class="d-flex justify-content-end align-items-center">
            <a href="controller/c_cerrar_sesion.php" class="btn btn-outline-danger me-2">Cerrar Sesión</a>

            <button class="btn btn-outline-success" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasCarrito" aria-controls="offcanvasCarrito">
                <i class="bi bi-cart4"></i> Carrito
            </button>
        </div>
    </div>
</nav>
<!-- Offcanvas para el carrito -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCarrito" aria-labelledby="offcanvasCarritoLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasCarritoLabel">Tu Carrito</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul id="carrito-lista">
        </ul>
        <div class="d-flex justify-content-between">
            <span>Total:</span>
            <span id="totalCarrito">$0.00</span>
        </div>
        <div class="d-grid mt-3">
            <button class="btn btn-success" id="realizar-compra-btn"> <i class="bi bi-cart-check-fill"></i> Ver mi
            carrito</button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h4>Filtros</h4>
                </div>
                <div class="card-body">
                    <h5>Categoría</h5>
                    <form id="filterForm" method="GET" action="productos.php">
                        <ul class="list-group">
                            <?php
                            include('conexion/conexion.php');
                            $sql = "SELECT * FROM categorias";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while ($categoria = mysqli_fetch_assoc($result)) {
                                    echo '<li class="list-group-item">
                                            <input type="checkbox" name="categoria[]" value="' . $categoria['categoria_id'] . '" id="categoria' . $categoria['categoria_id'] . '">
                                            <label for="categoria' . $categoria['categoria_id'] . '">' . $categoria['nombre_categoria'] . '</label>
                                          </li>';
                                }
                            } else {
                                echo '<li class="list-group-item">No hay categorías disponibles.</li>';
                            }
                            ?>
                        </ul>
                        <button type="submit" class="btn btn-success mt-3">Aplicar Filtro</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-md-9 mb-4">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                $categoriasSeleccionadas = isset($_GET['categoria']) ? $_GET['categoria'] : [];
                $sql = "SELECT p.producto_id, p.nombre, p.precio, p.cantidad_stock, p.imagen
                        FROM productos p";
                if (!empty($categoriasSeleccionadas)) {
                    $placeholders = implode(',', array_fill(0, count($categoriasSeleccionadas), '?'));
                    $sql .= " WHERE p.categoria_id IN ($placeholders)";
                }

                $stmt = mysqli_prepare($conn, $sql);
                if (!empty($categoriasSeleccionadas)) {
                    mysqli_stmt_bind_param($stmt, str_repeat('i', count($categoriasSeleccionadas)), ...$categoriasSeleccionadas);
                }

                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    while ($producto = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="col">
                            <div class="card shadow-sm">
                                <img src="img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top"
                                    alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                    <p class="text-muted">Cantidad en stock: <?php echo $producto['cantidad_stock']; ?></p>
                                    <p class="card-text"><strong>$<?php echo number_format($producto['precio'], 2); ?></strong>
                                    </p>
                                    <button class="btn btn-success agregar-carrito"
                                        data-id="<?php echo $producto['producto_id']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                        data-precio="<?php echo $producto['precio']; ?>"
                                        data-imagen="<?php echo htmlspecialchars($producto['imagen']); ?>">Agregar al
                                        carrito</button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No se encontraron productos.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <!-- Toast para notificación de agregar al carrito -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toast-notificacion" class="toast align-items-center text-bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Producto agregado al carrito.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

</div>
<script>
// Variables para el carrito
let carrito = JSON.parse(localStorage.getItem('carrito')) || []; // Cargar carrito desde localStorage
let totalCarrito = parseFloat(localStorage.getItem('totalCarrito')) || 0; // Cargar el total desde localStorage

// Función para actualizar el carrito en el localStorage
function actualizarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carrito));
    localStorage.setItem('totalCarrito', totalCarrito.toFixed(2)); // Guardamos el total con dos decimales
    document.getElementById('totalCarrito').innerText = `$${totalCarrito.toFixed(2)}`;
}

// Mostrar los productos en el carrito al cargar la página
function cargarCarrito() {
    let carritoLista = document.getElementById('carrito-lista');
    carritoLista.innerHTML = ''; // Limpiar la lista del carrito

    carrito.forEach(producto => {
        let itemCarrito = document.createElement('li');
        itemCarrito.classList.add('list-group-item');
        itemCarrito.innerHTML = `
            ${producto.nombre} - $${producto.precio.toFixed(2)}
            <button class="btn btn-danger btn-sm float-end eliminar-item" data-id="${producto.id}">X</button>
        `;
        let hr = document.createElement('hr');
        itemCarrito.appendChild(hr);
        carritoLista.appendChild(itemCarrito);
    });

    document.getElementById('totalCarrito').innerText = `$${totalCarrito.toFixed(2)}`;
}

// Función para agregar productos al carrito
document.querySelectorAll('.agregar-carrito').forEach(button => {
    button.addEventListener('click', (e) => {
        let producto = {
            usuario_id: parseInt(<?php echo htmlspecialchars($usuario_id); ?>),
            id: parseInt(e.target.getAttribute('data-id')), // Convertimos a número
            nombre: e.target.getAttribute('data-nombre'),
            precio: parseFloat(e.target.getAttribute('data-precio')),
            imagen: e.target.getAttribute('data-imagen'),
            cantidad: 1 // Se puede ajustar si manejas cantidades
        };

        // Comprobar si el producto ya está en el carrito
        let productoExistente = carrito.find(item => item.id === producto.id);
        if (productoExistente) {
            // Si ya existe, incrementar la cantidad
            productoExistente.cantidad += 1;
            totalCarrito += producto.precio;
        } else {
            // Si no existe, agregarlo al carrito
            carrito.push(producto);
            totalCarrito += producto.precio;
        }

        // Actualizar la lista visual del carrito
        let carritoLista = document.getElementById('carrito-lista');
        let itemCarrito = document.createElement('li');
        itemCarrito.classList.add('list-group-item');
        itemCarrito.innerHTML = `
            ${producto.nombre} - $${producto.precio.toFixed(2)} 
            <button class="btn btn-danger btn-sm float-end eliminar-item" data-id="${producto.id}">X</button>
        `;
        let hr = document.createElement('hr');
        itemCarrito.appendChild(hr);
        carritoLista.appendChild(itemCarrito);

        // Guardar los cambios en localStorage
        actualizarCarrito();

        // Enviar los datos al servidor con fetch
        fetch('controller/c_agregar_carrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(producto)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Producto agregado correctamente.');
                var toastEl = document.getElementById('toast-notificacion');
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            } else {
                console.log('Error al agregar producto al carrito.');
            }
        });
    });
});

// Eliminar productos del carrito
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('eliminar-item')) {
        let productoId = parseInt(e.target.getAttribute('data-id')); // Obtener el ID del producto a eliminar
        let productoEliminado = carrito.find(item => item.id === productoId); // Buscar el producto a eliminar
        carrito = carrito.filter(item => item.id !== productoId); // Eliminarlo del carrito
        totalCarrito -= productoEliminado.precio; // Restar su precio del total

        let usuarioId = parseInt(<?php echo htmlspecialchars($usuario_id); ?>); // Obtener el ID del usuario desde PHP

        // Enviar la solicitud para eliminar el producto del carrito en el servidor
        fetch('controller/c_eliminar_carrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ usuario_id: usuarioId, producto_id: productoId }) // Enviar tanto el usuario_id como el producto_id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar el elemento visual del carrito
                e.target.closest('li').remove();
                actualizarCarrito(); // Actualizar la lista de productos del carrito visualmente

                // Recalcular el total
                totalCarrito = carrito.reduce((total, producto) => total + producto.precio, 0);
                document.getElementById('totalCarrito').innerText = `$${totalCarrito.toFixed(2)}`;
            } else {
                alert('Error al eliminar el producto del carrito.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al intentar eliminar el producto.');
        });
    }
});

// Cargar el carrito desde localStorage cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    cargarCarrito();
});

const compraBtn = document.getElementById('realizar-compra-btn');
if (compraBtn) {
    compraBtn.addEventListener('click', () => {
        window.location.href = 'formulario_compra.php';
    });
}
</script>

<?php include('footer.php'); ?>
