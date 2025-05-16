<?php
// Conectar a la base de datos
$servername = "dbserver";
$username = "grupo24";
$password = "KooPh7tohL";
$database = "db_grupo24";

$conn = new mysqli($servername, $username, $password, $database);
$conn->set_charset("utf8"); // Establecer la codificación de caracteres a UTF-8
if ($conn->connect_error) {
    die("Error de conexion: " . $conn->connect_error);
}

// Obtener el nombre de la solicitud
$nombre = $_GET['nombre'];

// Consultar la base de datos para obtener los nombres que coinciden
$sql = "SELECT nombre FROM final_usuarios WHERE nombre LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = '%' . $nombre . '%';
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Error en la consulta SQL: " . $conn->error);
}

$response = array();
if ($result->num_rows > 0) {
    // Devolver los nombres en formato JSON
    while ($row = $result->fetch_assoc()) {
        $response[] = array(
            "nombre" => $row['nombre']
        );
    }
    echo json_encode($response);
} else {
    // No se encontró ningún usuario para el nombre seleccionado
    echo json_encode(array("error" => "No se encontraron usuarios con ese nombre."));
}

$conn->close();
?>