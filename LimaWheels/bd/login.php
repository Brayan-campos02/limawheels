<?php
// Incluir el archivo de conexión
include('bd/conexion.php');

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
        if (password_verify($contrasena, $usuario['contrasena'])) {
            // Iniciar sesión y redirigir al usuario
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre_completo'] = $usuario['nombre_completo'];

            // Redirigir al archivo sesion-iniciada.html
            header("Location: sesion-iniciada.html");
            exit(); // Asegúrate de que el script se detenga aquí
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Correo electrónico no encontrado.";
    }
}
?>

<!-- Formulario de inicio de sesión -->
<form action="bd/login.php" method="POST">
    <label for="correo_electronico">Correo Electrónico:</label>
    <input type="email" name="correo_electronico" id="correo_electronico" required>

    <label for="contrasena">Contraseña:</label>
    <input type="password" name="contrasena" id="contrasena" required>

    <button type="submit">Iniciar Sesión</button>
</form>

