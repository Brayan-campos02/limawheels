<?php
session_start();
include(__DIR__.'/../bd/conexion.php');

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT nombre_completo, apellido_completo, correo_electronico, contrasena FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Procesar cambio de contraseña
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cambiar_contrasena'])) {
    // Aquí va tu lógica de cambio de contraseña
    // Por ejemplo, verificar actual, nueva, confirmación, etc.
}

// Verificar que la consulta devuelve datos
if ($result->num_rows === 0) {
    // Puedes guardar mensaje de error en sesión si deseas
    $_SESSION['error'] = "No se encontraron datos del usuario.";
}

// Redirigir siempre a indexusuario.html
header("Location: ../limawheels/indexusuario.html");
exit();
?>
