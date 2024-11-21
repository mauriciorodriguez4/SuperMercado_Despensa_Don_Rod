<?php include('header.php'); ?>
<?php
session_start();
?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row w-100">
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg p-4" style="border-radius: 1rem;">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-success">Registro de Cliente</h2>
                    <p class="text-muted">Únete a nuestro supermercado</p>
                </div>

                <!-- Mostrar alertas -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php elseif (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <!-- Formulario de registro -->
                <form action="controller/c_registro.php" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="name" name="nombre" placeholder="Nombre completo" required>
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="address" name="direccion" placeholder="Dirección" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="number" class="form-control" id="phone" name="telefono" placeholder="Ej: 12345678" maxlength="8" required oninput="validarLongitud(this)">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@correo.com" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="contraseña" placeholder="Crea una contraseña" required>
                    </div>
                    <input type="hidden" name="rol" value="cliente">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">Registrar</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <small class="text-muted">¿Ya tienes una cuenta? <a href="login.php" class="text-success">Inicia sesión</a></small>
                </div>
            </div>
        </div>

        <!-- Card Descripción -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg p-4" style="border-radius: 1rem;">
                <div class="text-center mb-4">
                    <img src="img/logo.png" alt="Logo" class="img-fluid" style="max-width: 164px;">
                    <h3 class="fw-bold text-success mt-3">La Despensa de <br>Don Rod</h3>
                </div>
                <p class="text-muted">Somos un supermercado líder...</p>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
<script>
    function validarLongitud(input) {
        if (input.value.length > 8) {
            input.value = input.value.slice(0, 8);
        }
    }
</script>
