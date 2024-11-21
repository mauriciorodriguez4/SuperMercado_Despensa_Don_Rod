<?php include('header.php'); ?>
<?php include('conexion/conexion.php'); ?>
<?php
session_start();
// Verificar si la sesión está iniciada y si existe el nombre
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "Invitado";
?>

<nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
    <div class="container text-light">
        <div class="w-100 d-flex justify-content-between">
            <div>
                <i class="bi bi-envelope-at-fill mx-2"></i>
                <a class="navbar-sm-brand text-light text-decoration-none" href="mailto:info@company.com">despensadedonrod@rodcode.com</a>
                <i class="bi bi-telephone-forward-fill mx-2"></i>
                <a class="navbar-sm-brand text-light text-decoration-none" href="tel:010-020-0340">24478912</a>
            </div>
            <div>
                <a class="text-light" href="https://www.instagram.com/" target="_blank"><i class="bi bi-instagram"></i></a>
                <a class="text-light" href="https://twitter.com/" target="_blank"><i class="bi bi-twitter-x"></i></a>
                <a class="text-light" href="https://www.linkedin.com/" target="_blank"><i class="bi bi-linkedin"></i></a>
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

        <!-- Mensaje de bienvenida -->
        <div class="d-flex justify-content-start align-items-center ms-3">
            <h2 class="m-0">Hola, <?php echo htmlspecialchars($nombre_usuario); ?> <i class="bi bi-person-circle"></i></h2>
        </div>

        <!-- Botones adicionales y carrito -->
        <div class="d-flex justify-content-end align-items-center">
            <a href="productos.php" class="btn btn-outline-primary me-2">Ver Productos</a>
            <a href="controller/c_cerrar_sesion.php" class="btn btn-outline-danger me-2">Cerrar Sesión</a>

           
        </div>
    </div>
</nav>

<section class="container py-5">
    <div class="row text-center pt-3">
        <div class="col-lg-6 m-auto">
            <h1 class="h1">Productos de Tv y Video</h1>
            <p>
                Explora nuestra selección exclusiva de productos de Tv y Video.
            </p>
        </div>
    </div>

    <div id="tvVideoCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            include('conexion/conexion.php'); 
            
            $query = "SELECT p.producto_id, p.nombre, p.imagen 
                      FROM productos p 
                      INNER JOIN categorias c ON p.categoria_id = c.categoria_id 
                      WHERE c.nombre_categoria = 'Tv y Video'";
            $result = mysqli_query($conn, $query);

            // Verificar si hay resultados y generar el carrusel
            if (mysqli_num_rows($result) > 0) {
                $active = true; 
                while ($producto = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="carousel-item <?= $active ? 'active' : '' ?>">
                        <img src="./img/<?= htmlspecialchars($producto['imagen']); ?>" class="d-block w-100" alt="<?= htmlspecialchars($producto['nombre']); ?>" style=" object-fit: cover;">
                    </div>
            <?php
                    $active = false; 
                }
            } else {
                echo "<p class='text-center'>No hay productos disponibles en la categoría 'Tv y Video'.</p>";
            }
            ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#tvVideoCarousel" data-bs-slide="prev" style="background-color: rgba(0, 0, 0, 0.2)";>
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#tvVideoCarousel" data-bs-slide="next" style="background-color: rgba(0, 0, 0, 0.2)";>
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</section>

<section class="container my-5">
    <h2 class="text-center mb-4 text-success">Nuestros Productos</h2>
    
    <!-- Carrusel para los productos -->
    <div id="productosCarrusel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            include('conexion/conexion.php'); 

            $query = "SELECT producto_id, nombre, precio, imagen 
                      FROM productos 
                      LIMIT 8";
            $result = mysqli_query($conn, $query);

            // Verificar si hay resultados
            if (mysqli_num_rows($result) > 0) {
                $active = true; 
                $counter = 0; 
                while ($producto = mysqli_fetch_assoc($result)) {                    
                    if ($counter % 4 == 0) {
                        if ($counter > 0) {
                            echo '</div></div>';
                        }                        
                        echo '<div class="carousel-item' . ($active ? ' active' : '') . '">';
                        echo '<div class="row">';
                        $active = false; 
                    }
                    ?>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-lg">
                            <img src="./img/<?= htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?= htmlspecialchars($producto['nombre']); ?>" style="max-height: 250px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($producto['nombre']); ?></h5>
                                <p class="card-text">Precio: $<?= number_format($producto['precio'], 2); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                    $counter++; 
                }
                echo '</div></div>'; 
            } else {
                echo "<p class='text-center'>No hay productos disponibles.</p>";
            }
            ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#productosCarrusel" data-bs-slide="prev" style="background-color: rgba(0, 0, 0, 0.2);">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productosCarrusel" data-bs-slide="next" style="background-color: rgba(0, 0, 0, 0.2);">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</section>


<div class="text-center mb-4 mt-4">
    <a href="productos.php" class="btn btn-primary btn-lg">Ver más productos</a>
</div>
</div>

<?php include('footer.php'); ?>