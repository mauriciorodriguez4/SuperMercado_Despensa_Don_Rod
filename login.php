<?php 
require 'conexion/conexion.php';
session_start();
include('header.php'); 
?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 400px; border-radius: 1rem;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-success mt-3">Bienvenid@ a <br>Despensa de Don Rod</h2>
            <img src="img/logo.png" alt="Supermercado" width="200">
            <p class="text-muted">Tienes que iniciar sesión para continuar</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="controller/c_logueo.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="email" placeholder="Ingresa tu correo" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="contraseña" placeholder="Ingresa tu contraseña" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg">Entrar</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <small class="text-muted">¿No tienes cuenta? <a href="registrarse.php" class="text-success">Regístrate</a></small>
        </div>
    </div>
</div>
