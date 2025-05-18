<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo_electronico'];
    $contrasena = $_POST['contrasena'];
    $ip = $_SERVER['REMOTE_ADDR']; // Obtener la dirección IP del usuario

    // Consulta para obtener el usuario
    $sql = "SELECT * FROM usuarios WHERE correo_electronico = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Registrar intento de acceso (antes de verificar credenciales)
    $usuario_id = 0;
    $exitoso = 0;
    
    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $usuario_id = $usuario['id'];
        
        if ($contrasena === $usuario['contrasena']) {
            $exitoso = 1;
            
            // Iniciar sesión y redirigir al usuario
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre_completo'] = $usuario['nombre_completo'];

            // Registrar acceso exitoso
            $log_sql = "INSERT INTO logs_acceso (usuario_id, ip_address, exitoso) VALUES (?, ?, ?)";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->bind_param('isi', $usuario_id, $ip, $exitoso);
            $log_stmt->execute();

            header("Location: /trabajofinal/limawheels/indexusuario.html");
            exit();
        }
    }

    // Registrar acceso fallido (si no fue exitoso)
    if ($exitoso == 0) {
        $log_sql = "INSERT INTO logs_acceso (usuario_id, ip_address, exitoso) VALUES (?, ?, ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param('isi', $usuario_id, $ip, $exitoso);
        $log_stmt->execute();
    }

    // Mostrar mensajes de error
    if ($resultado->num_rows == 0) {
        echo "Correo electrónico no encontrado.";
    } else {
        echo "Contraseña incorrecta.";
    }
}
?>