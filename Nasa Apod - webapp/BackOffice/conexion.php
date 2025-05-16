<?php
// Configuración de la conexión a la base de datos
$servername = "dbserver";
$username = "grupo24";
$password = "KooPh7tohL";
$database = "db_grupo24";

// Conexión a la base de datos
$dblink = mysqli_connect($servername, $username, $password, $database);

$sql = "SELECT MAX(fecha) AS ultima_fecha FROM final_fechas";
$resultado = mysqli_query($dblink, $sql);
if ($resultado->num_rows > 0) {
    // Obtener la fila de resultados como un array asociativo
    $fila = $resultado->fetch_assoc();
    // Obtener la última fecha
    $ultimaFecha = $fila['ultima_fecha'];
    // Incrementar la última fecha en un día
    $fechaIncrementada = date("Y-m-d", strtotime($ultimaFecha . " +1 day"));
    // Imprimir la fecha incrementada
    echo "La última fecha en la tabla es: " . $ultimaFecha . "<br>";
    echo "La fecha incrementada es: " . $fechaIncrementada . "<br>";
} else {
    echo "No se encontraron resultados";
}

$dia = date("Y-m-d");
// URL de la API APOD
$url = "https://api.nasa.gov/planetary/apod?start_date=" . $fechaIncrementada . "&end_date=" . $dia . "&api_key=ejlLT3I79ssffEOFJye0RBRjtcsZGy8SbbFgTr5m";
//$anio = "2002";
//$url = "https://api.nasa.gov/planetary/apod?start_date=" . $anio . "-01-01&end_date=" . $anio . "-06-15&api_key=ejlLT3I79ssffEOFJye0RBRjtcsZGy8SbbFgTr5m";

// Realizar la solicitud a la API
$response = file_get_contents($url);

// Escribir los datos en un archivo
$file_path = 'datos_apod.json';
if (file_put_contents($file_path, $response)) {
    echo "Los datos se han guardado correctamente en el archivo $file_path<br>";
} else {
    echo "Hubo un error al intentar guardar los datos en el archivo.<br>";
}

// Decodificar la respuesta JSON
$data = json_decode($response, true);

// Insertar los datos en la base de datos

for ($i = 0; $i < count($data); $i++) {
    $date = $data[$i]['date'];
    $title = str_replace("'", "\'", $data[$i]['title']);
    $explanation = str_replace("'", "\'", $data[$i]['explanation']);
    $image_url = $data[$i]['url'];
    if (($copyright= $data[$i]['copyright']) !== null) {
		$copyright = str_replace("'", "\'", $copyright);
		$copyright = str_replace("\n", " ", $copyright);
	}
	//Insertar datos en la tabla 'fechas'
	$sql = "INSERT INTO final_fechas (fecha) VALUES ('$date')";
	if (mysqli_query($dblink, $sql)) {
		$fecha_id = mysqli_insert_id($dblink);
		echo "ID de la fecha insertada: " . $fecha_id . "<br>";
	} else {
		echo "Error al insertar datos: " . mysqli_error($dblink);
	}

	//Insertar datos en la tabla 'titulos'
	$sql = "INSERT INTO final_titulos (titulo) VALUES ('$title')";
	if (mysqli_query($dblink, $sql)) {
		$titulo_id = mysqli_insert_id($dblink);
		echo "ID del titulo insertado: " . $titulo_id . "<br>";
	} else {
		echo "Error al insertar datos: " . mysqli_error($dblink);
	}

	//Insertar datos en la tabla 'explicaciones'
	$sql = "INSERT INTO final_explicaciones (explicacion) VALUES ('$explanation')";
	if (mysqli_query($dblink, $sql)) {
		$explicacion_id = mysqli_insert_id($dblink);
		echo "ID de la explicacion insertada: " . $explicacion_id . "<br>";
	} else {
		echo "Error al insertar datos: " . mysqli_error($dblink);
	}

	//Insertar datos en la tabla 'url_imagenes'
	$sql = "INSERT INTO final_url_imagenes (url_imagen) VALUES ('$image_url')";
	if (mysqli_query($dblink, $sql)) {
		$url_imagen_id = mysqli_insert_id($dblink);
		echo "ID de la url_imagen insertada: " . $url_imagen_id . "<br>";
	} else {
		echo "Error al insertar datos: " . mysqli_error($dblink);
	}

	//Insertar datos en la tabla 'copyright'
	$sql = "INSERT INTO final_copyright (copyright) VALUES ('$copyright')";
	if (mysqli_query($dblink, $sql)) {
		$copyright_id = mysqli_insert_id($dblink);
		echo "ID del copytight insertado: " . $copyright_id . "<br>";
	} else {
		echo "Error al insertar datos: " . mysqli_error($dblink);
	}

	//Insertar todos los ID en la tabla 'apod_images'
	$sql = "INSERT INTO final_apod_images (fecha_id, titulo_id, explicacion_id, url_imagen_id, copyright_id) VALUES ('$fecha_id', '$titulo_id', '$explicacion_id', '$url_imagen_id', '$copyright_id')";
	if (mysqli_query($dblink, $sql)) {
		echo "Datos insertados en la tabla apod_images.<br>";
	} else {
		echo "Error al insertar datos: " . mysqli_error($dblink);
	}
}
// Cerrar la conexión a la base de datos
mysqli_close($dblink);

?>
