<?php
$host = 'bjag5d7mhmys26ix4gsk-mysql.services.clever-cloud.com';
$usuario = 'uxlfokp1njwpzhnt';
$contrasena = 'B5Wz8ewVuaEOklqMILrX';
$base_de_datos = 'bjag5d7mhmys26ix4gsk';

$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
