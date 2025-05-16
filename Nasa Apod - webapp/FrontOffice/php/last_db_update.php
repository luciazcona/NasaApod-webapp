<?php
// Conectar a la base de datos
$servername = "dbserver";
$username = "grupo24";
$password = "KooPh7tohL";
$database = "db_grupo24";

$conn = new mysqli($servername, $username, $password, $database);
$conn->set_charset("utf8"); // Establecer la codificación de caracteres a UTF-8
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT fecha 
        FROM final_fechas 
        WHERE id = (
            SELECT fecha_id 
            FROM final_apod_images 
            ORDER BY fecha_id DESC 
            LIMIT 1
        );";

$result = $conn->query($sql);

if ($result === false) {
    die("Error en la consulta SQL: " . $conn->error);
}

// Verificar si se obtuvo algún resultado
if ($result->num_rows > 0) {
    // Obtener el resultado como un array asociativo
    $row = $result->fetch_assoc();

    // Convertir el array asociativo en JSON
    echo json_encode($row);
} else {
    echo "No se encontraron resultados.";
}

$conn->close();
?>
