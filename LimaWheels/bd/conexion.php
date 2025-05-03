<?php
// Configuración de la base de datos
$host = 'localhost'; // Dirección del servidor
$usuario = 'root';   // Usuario de la base de datos
$contrasena = '';    // Contraseña de la base de datos (por defecto en XAMPP es vacío)
$base_de_datos = 'lima_wheels_bd'; // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}
?>
