<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo_electronico'];
    $contrasena = $_POST['contrasena'];
    $ip = $_SERVER['REMOTE_ADDR'];

    $sql = "SELECT * FROM usuarios WHERE correo_electronico = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $usuario_id = 0;
    $exitoso = 0;

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $usuario_id = $usuario['id'];

        // Verifica la contraseña usando password_verify
        if (password_verify($contrasena, $usuario['contrasena'])) {
            $exitoso = 1;

            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre_completo'] = $usuario['nombre_completo'];

            $log_sql = "INSERT INTO logs_acceso (usuario_id, ip_address, exitoso) VALUES (?, ?, ?)";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->bind_param('isi', $usuario_id, $ip, $exitoso);
            $log_stmt->execute();

            header("Location: /trabajofinal/limawheels/indexusuario.html");
            exit();
        }
    }

    // Log de intento fallido
    $log_sql = "INSERT INTO logs_acceso (usuario_id, ip_address, exitoso) VALUES (?, ?, ?)";
    $log_stmt = $conn->prepare($log_sql);
    $log_stmt->bind_param('isi', $usuario_id, $ip, $exitoso);
    $log_stmt->execute();

    // Mensaje de error
    if ($resultado->num_rows == 0) {
        echo "Correo electrónico no encontrado.";
    } else {
        echo "Contraseña incorrecta.";
    }
}

?>