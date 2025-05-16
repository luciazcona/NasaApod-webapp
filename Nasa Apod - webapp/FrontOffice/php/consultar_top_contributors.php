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

// Consultar la base de datos para obtener los 10 principales contribuyentes
$sql = "SELECT final_copyright.copyright, COUNT(*) AS count
        FROM final_apod_images
        INNER JOIN final_copyright ON final_apod_images.copyright_id = final_copyright.id
        GROUP BY copyright
        ORDER BY count DESC
        LIMIT 14;";

$result = $conn->query($sql);

if ($result === false) {
    die("Error en la consulta SQL: " . $conn->error);
}

// Crear un array para almacenar los resultados
$top_contributors = array();
while ($row = $result->fetch_assoc()) {
    $top_contributors[] = $row['copyright'];
}

// Devolver los resultados como JSON
echo json_encode($top_contributors);

$conn->close();
?>
