<?php include('header.php');
include('conexion/conexion.php');
?>

<?php
session_start();

if (isset($_SESSION['nombre'])) {
    $nombre_usuario = $_SESSION['nombre'];
} else {
    // Si no está iniciada la sesión o el nombre no está definido, mostrar un mensaje
    // echo "<h2>No has iniciado sesión correctamente.</h2>";
}

$total_productos = 0;
$total_empleados = 0;
$total_categoria = 0;
$total_compras = 0;


try {    
    $resultado = mysqli_query($conn, "SELECT COUNT(*) AS total FROM productos");
    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $total_productos = $fila['total'];
    }

    $resultado = mysqli_query($conn, "SELECT COUNT(*) AS total FROM empleados");
    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $total_empleados = $fila['total'];
    }

    $resultado = mysqli_query($conn, "SELECT COUNT(*) AS total FROM categorias");
    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $total_categoria = $fila['total'];
    }
    $resultado = mysqli_query($conn, "SELECT COUNT(*) AS total FROM detalles_compra");
    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $total_compras = $fila['total'];
    }
} catch (Exception $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="admin.php">
            <img src="img/logo.png" alt="Logo" width="50" height="50"> La Despensa de Don Rod
        </a>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 bg-light p-4">
            <h2 class="mb-4">Administración</h2>
            <ul class="nav flex-column">
                <li class="nav-item mb-4">
                    <a class="btn btn-secondary w-100" href="./admin.php">Inicio</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="btn btn-success w-100" href="vistas/admin_empleados.php">Gestión de Empleados</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="btn btn-success w-100" href="vistas/admin_productos.php">Gestión Productos</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="btn btn-success w-100" href="vistas/admin_categorias.php">Gestión Categorías</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="btn btn-success w-100" href="vistas/admin_compras.php">Detalle de Compras</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="btn btn-danger w-100" href="controller/c_cerrar_sesion.php">Cerrar sesión</a>
                </li>
            </ul>
        </div>
        
        <div class="col-md-9 p-4">
            <h2>Bienvenido, @<?php echo htmlspecialchars($nombre_usuario); ?></h2>
            <h6 class="text-muted">Panel de Administrador</h6>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Total de Productos</h5>
                            <p class="card-text"><?php echo $total_productos; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Total de Empleados</h5>
                            <p class="card-text"><?php echo $total_empleados; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Total de Compras</h5>
                            <p class="card-text"><?php echo $total_compras; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Total categorias</h5>
                            <p class="card-text"><?php echo $total_categoria; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-5">
                <h3 class="text-center">Estadísticas Despensa Don Rod</h3>
                <canvas id="chartEstadisticas" width="850" height="450"></canvas>
            </div>

        </div>
    </div>

</div>

<?php include('footer.php'); ?>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('chartEstadisticas').getContext('2d');

        var chartEstadisticas = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Productos', 'Empleados', 'Categorías', 'Compras'],
                datasets: [{
                    label: 'Estadísticas Generales',
                    data: [
                        <?php echo $total_productos; ?>,
                        <?php echo $total_empleados; ?>,
                        <?php echo $total_categoria; ?>,
                        <?php echo $total_compras; ?>
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false, 
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    });
</script>