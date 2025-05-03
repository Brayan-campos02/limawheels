<?php
// Incluir el archivo de conexión
include('bd/conexion.php');

// Comprobar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_completo = $_POST['nombre_completo'];
    $apellido_completo = $_POST['apellido_completo'];
    $correo_electronico = $_POST['correo_electronico'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Verificar si las contraseñas coinciden
    if ($contrasena != $confirmar_contrasena) {
        echo "Las contraseñas no coinciden.";
    } else {
        // Encriptar la contraseña
        $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);

        // Verificar si el correo ya existe
        $sql = "SELECT * FROM usuarios WHERE correo_electronico = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $correo_electronico);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            echo "El correo electrónico ya está registrado.";
        } else {
            // Insertar el nuevo usuario en la base de datos
            $sql = "INSERT INTO usuarios (nombre_completo, apellido_completo, correo_electronico, contrasena) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $nombre_completo, $apellido_completo, $correo_electronico, $contrasena_encriptada);

            if ($stmt->execute()) {
                echo "Cuenta creada exitosamente.";
                // Redirigir al archivo sesion-iniciada.html
                header("Location: sesion-iniciada.html"); 
                exit(); // Asegúrate de que el script se detenga aquí
            } else {
                echo "Error al crear la cuenta: " . $conn->error;
            }
        }
    }
}
?>

<!-- Formulario de registro -->
<form action="bd/registro.php" method="POST">
    <label for="nombre_completo">Nombre Completo:</label>
    <input type="text" name="nombre_completo" id="nombre_completo" required>

    <label for="apellido_completo">Apellido Completo:</label>
    <input type="text" name="apellido_completo" id="apellido_completo" required>

    <label for="correo_electronico">Correo Electrónico:</label>
    <input type="email" name="correo_electronico" id="correo_electronico" required>

    <label for="contrasena">Contraseña:</label>
    <input type="password" name="contrasena" id="contrasena" required>

    <label for="confirmar_contrasena">Confirmar Contraseña:</label>
    <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" required>

    <button type="submit">Registrar Cuenta</button>
</form>
