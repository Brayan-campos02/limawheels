<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'conexion.php';

try {
    $query = "SELECT marca, modelo, imagen, precio, descripcion, condicion, url FROM automoviles";
    $result = $conn->query($query);
    
    $autos = [];
    while($row = $result->fetch_assoc()) {
        $autos[] = $row;
    }
    
    // Devuelve siempre un array, incluso si está vacío
    echo json_encode(['data' => $autos]);
    
} catch(Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>