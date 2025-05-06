<?php
// Incluir el archivo de conexión
include('conexion.php');

// Comprobar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo_electronico'];
    $contrasena = $_POST['contrasena'];

    // Consulta para obtener el usuario
    $sql = "SELECT * FROM usuarios WHERE correo_electronico = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si existe el usuario
    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Verificar la contraseña
        if ($contrasena === $usuario['contrasena']) {
            // Iniciar sesión y redirigir al usuario
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre_completo'] = $usuario['nombre_completo'];

            // Redirigir al archivo sesion-iniciada.html
            header("Location: /trabajofinal/limawheels/Principal/sesion-iniciada.html");
            exit(); // Asegúrate de que el script se detenga aquí
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Correo electrónico no encontrado.";
    }
}
?>
