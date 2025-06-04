<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_completo = $_POST['nombre_completo'];
    $apellido_completo = $_POST['apellido_completo'];
    $correo_electronico = $_POST['correo_electronico'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    if ($contrasena != $confirmar_contrasena) {
        echo "<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>";
        exit();
    }

    if (
        strlen($contrasena) < 8 ||
        !preg_match('/[A-Z]/', $contrasena) ||
        !preg_match('/[0-9]/', $contrasena) ||
        !preg_match('/[\W_]/', $contrasena)
    ) {
        echo "<script>alert('La contraseña debe tener al menos 8 caracteres, una letra mayúscula, un número y un carácter especial.'); window.history.back();</script>";
        exit();
    }

    $sql = "SELECT * FROM usuarios WHERE correo_electronico = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $correo_electronico);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "<script>alert('El correo electrónico ya está registrado.'); window.history.back();</script>";
        exit();
    }

    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

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
}
?>
