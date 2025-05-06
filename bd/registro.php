<?php
// Incluir el archivo de conexión
include('conexion.php');

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
        exit();
    }

    // Verificar si el correo ya existe
    $sql = "SELECT * FROM usuarios WHERE correo_electronico = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $correo_electronico);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "El correo electrónico ya está registrado.";
        exit();
    }

    // Insertar el nuevo usuario (sin encriptar la contraseña)
    $sql = "INSERT INTO usuarios (nombre_completo, apellido_completo, correo_electronico, contrasena) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $nombre_completo, $apellido_completo, $correo_electronico, $contrasena);

    if ($stmt->execute()) {
        // Registro exitoso, redirigir
        header("Location: /trabajofinal/limawheels/Principal/sesion-iniciada.html");
        exit();
    } else {
        echo "Error al crear la cuenta: " . $conn->error;
    }
}
?>
