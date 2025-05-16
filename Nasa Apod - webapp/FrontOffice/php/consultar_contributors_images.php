<?php
// Conectar a la base de datos
$servername = "dbserver";
$username = "grupo24";
$password = "KooPh7tohL";
$database = "db_grupo24";

$conn = new mysqli($servername, $username, $password, $database);
$conn->set_charset("utf8"); // Establecer la codificaci�n de caracteres a UTF-8
if ($conn->connect_error) {
    die("Error de conexion: " . $conn->connect_error);
}

// Obtener el t�tulo de la solicitud
$copyright = $_GET['copyright'];

// Consultar la base de datos para obtener el t�tulo, la descripci�n y la URL de la imagen
$sql = "SELECT final_titulos.titulo, final_fechas.fecha, final_explicaciones.explicacion, final_url_imagenes.url_imagen, final_copyright.copyright
        FROM final_apod_images
        INNER JOIN final_titulos ON final_apod_images.titulo_id = final_titulos.id
        INNER JOIN final_fechas ON final_apod_images.fecha_id = final_fechas.id
        INNER JOIN final_url_imagenes ON final_apod_images.url_imagen_id = final_url_imagenes.id
        INNER JOIN final_explicaciones ON final_apod_images.explicacion_id = final_explicaciones.id
        INNER JOIN final_copyright ON final_apod_images.copyright_id = final_copyright.id
        WHERE final_copyright.copyright = '$copyright'";

$result = $conn->query($sql);

if ($result === false) {
    die("Error en la consulta SQL: " . $conn->error);
}

if ($result->num_rows > 0) {
    // Devolver el t�tulo, la descripci�n y la URL de la imagen en formato JSON
    $response = array();
    while ($row = $result->fetch_assoc()) {
        $response[] = array(
            "titulo" => $row['titulo'],
            "fecha" => $row['fecha'],
            "explicacion" => $row['explicacion'],
            "url_imagen" => $row['url_imagen'],
            "copyright" => $row['copyright']
        );
    }
    echo json_encode($response);
} else {
    // No se encontro ninguna imagen para el titulo seleccionado
    echo json_encode(array("error" => "No image was found for the input title"));
}

$conn->close();
?>
