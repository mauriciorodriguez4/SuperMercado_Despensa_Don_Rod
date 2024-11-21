<?php
include('../conexion/conexion.php');  // Incluir la conexión a la base de datos
session_start();  // Iniciar la sesión para almacenar el mensaje de error

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['nombre'];
    $address = $_POST['direccion'];
    $phone = $_POST['telefono'];
    $email = $_POST['email'];
    $password = $_POST['contraseña'];  // No encriptado
    $role = $_POST['rol'];

    if (empty($name) || empty($address) || empty($phone) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "El correo electrónico ya está registrado.";
            header("Location: ../registrarse.php");  // Redirige de nuevo al formulario de registro
            exit();
        } else {
            // Insertar el nuevo usuario en la base de datos sin encriptar la contraseña
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, direccion, telefono, email, contraseña, rol) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $address, $phone, $email, $password, $role);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Registro exitoso. Ahora puedes iniciar sesión.";
                header("Location: ../login.php"); 
                exit();
            } else {
                $_SESSION['error'] = "Error en la ejecución: " . $stmt->error;
            }
        }
    }
}
?>
