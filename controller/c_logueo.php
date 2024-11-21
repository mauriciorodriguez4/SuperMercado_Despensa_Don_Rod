<?php 
require '../conexion/conexion.php';
session_start();

$email = $_POST['email'];
$contraseña = $_POST['contraseña'];

// Consultar el rol junto con el conteo de coincidencias
$query = "SELECT COUNT(*) as contar, usuario_id, rol, nombre FROM usuarios WHERE email='$email' AND contraseña='$contraseña'";
$consulta = mysqli_query($conn, $query);

$array = mysqli_fetch_array($consulta);

if ($array['contar'] > 0) {
    // Guardar el rol en la sesión
    $_SESSION['rol'] = $array['rol'];
    $_SESSION['nombre'] = $array['nombre'];
    $_SESSION['usuario_id'] = $array['usuario_id'];

    
    // Redireccionar según el rol
    if ($array['rol'] == 'cliente') {
        header("Location: ../index.php");
        exit;
    } elseif ($array['rol'] == 'admin') {
        header("Location: ../admin.php");
        exit;
    } else {
        header("Location: ../index.php");
    }
} else {
    // En caso de datos incorrectos, establecer un mensaje de error en la sesión y redirigir
    $_SESSION['error'] = "Correo electrónico o contraseña incorrectos.";
    header("Location: ../login.php");  
    exit;
}
?>
