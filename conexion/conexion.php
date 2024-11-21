<?php
$host = 'db-supermercado.c1e6ys0gakjf.us-east-1.rds.amazonaws.com';      
$dbname = 'super_mercados';
$username = 'admin';       
$password = 'Admin123';            

// Crear la conexión
$conn = mysqli_connect($host, $username, $password, $dbname);

// Verificar la conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
//echo "Conexión exitosa a la base de datos";
?>
