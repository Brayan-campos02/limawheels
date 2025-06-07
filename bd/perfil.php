<?php
session_start();
include(__DIR__.'/../bd/conexion.php'); // Ajusta según tu estructura

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Redirigir a indexusuario.html si no se solicita el perfil directamente
if (!isset($_GET['ver_perfil'])) {
    header("Location: indexusuario.html");
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
    // [Mantén todo el código de cambio de contraseña que ya tienes]
}

// Pasar datos a la vista HTML
$datos_usuario = [
    'nombre_completo' => htmlspecialchars($usuario['nombre_completo']),
    'apellido_completo' => htmlspecialchars($usuario['apellido_completo']),
    'correo_electronico' => htmlspecialchars($usuario['correo_electronico']),
    'contrasena' => htmlspecialchars($usuario['contrasena']),
    'mensaje' => $mensaje,
    'error' => $error
];

// Verificar que la consulta devuelve datos
if ($result->num_rows === 0) {
    die("Error: No se encontraron datos del usuario.");
}

// Incluir navbar.php con ruta correcta
function includeWithPath($path) {
    if (file_exists(__DIR__.'/../'.$path)) {
        include(__DIR__.'/../'.$path);
    } else {
        die("Error: No se pudo encontrar el archivo ".$path);
    }
}

includeWithPath('perfil.html');
?>