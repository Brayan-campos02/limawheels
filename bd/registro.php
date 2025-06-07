<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_completo = trim($_POST['nombre_completo']);
    $apellido_completo = trim($_POST['apellido_completo']);
    $correo_electronico = trim($_POST['correo_electronico']);
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Validación de nombres y apellidos (no números)
    if (preg_match('/[0-9]/', $nombre_completo)) {
        echo "<script>alert('El nombre no puede contener números.'); window.history.back();</script>";
        exit();
    }

    if (preg_match('/[0-9]/', $apellido_completo)) {
        echo "<script>alert('El apellido no puede contener números.'); window.history.back();</script>";
        exit();
    }

    // Validación de campos vacíos
    if (empty($nombre_completo) || empty($apellido_completo) || empty($correo_electronico) || empty($contrasena) || empty($confirmar_contrasena)) {
        echo "<script>alert('Todos los campos son obligatorios.'); window.history.back();</script>";
        exit();
    }

    // Validación de estructura de correo electrónico
    if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('El formato del correo electrónico no es válido. Debe ser usuario@dominio.extensión'); window.history.back();</script>";
        exit();
    }

    // Validación de dominio de correo
    $dominio = substr(strrchr($correo_electronico, "@"), 1);
    if (!checkdnsrr($dominio, 'MX')) {
        echo "<script>alert('El dominio del correo electrónico no existe o no puede recibir mensajes.'); window.history.back();</script>";
        exit();
    }

    // Validación de contraseñas coincidentes
    if ($contrasena != $confirmar_contrasena) {
        echo "<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>";
        exit();
    }

    // Validación de espacios en contraseña
    if (preg_match('/\s/', $contrasena)) {
        echo "<script>alert('La contraseña no puede contener espacios.'); window.history.back();</script>";
        exit();
    }

    // Validación de fortaleza de contraseña
    if (
        strlen($contrasena) < 8 ||
        !preg_match('/[A-Z]/', $contrasena) ||
        !preg_match('/[0-9]/', $contrasena) ||
        !preg_match('/[\W_]/', $contrasena)
    ) {
        echo "<script>alert('La contraseña debe tener al menos 8 caracteres, una letra mayúscula, un número y un carácter especial.'); window.history.back();</script>";
        exit();
    }

    // Validación de email único
    $sql = "SELECT * FROM usuarios WHERE correo_electronico = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $correo_electronico);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "<script>alert('El correo electrónico ya está registrado.'); window.history.back();</script>";
        exit();
    }

    // Hash de contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Inserción en la base de datos
    $sql = "INSERT INTO usuarios (nombre_completo, apellido_completo, correo_electronico, contrasena)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $nombre_completo, $apellido_completo, $correo_electronico, $contrasena_hash);

    if ($stmt->execute()) {
        echo "<script>alert('Registro exitoso.'); window.location.href = '/trabajofinal/limawheels/indexusuario.html';</script>";
        exit();
    } else {
        echo "<script>alert('Error al crear la cuenta: " . $conn->error . "'); window.history.back();</script>";
    }

    if ($stmt->execute()) {
    // Iniciar sesión automáticamente
    session_start();
    $_SESSION['usuario_id'] = $conn->insert_id; // ID del nuevo usuario
    $_SESSION['nombre_completo'] = $nombre_completo;
    
    // Redirigir a página PHP que puede manejar sesiones
    header("Location: perfil.php");
    exit();
    }
}
?>