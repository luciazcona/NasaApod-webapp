<?php
// Conexión a la base de datos (ajusta los valores según tu configuración)
$servername = "dbserver";
$username = "grupo24";
$password = "KooPh7tohL";
$database = "db_grupo24";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre = 'admin';
$email = 'admin@gmail.com';
$contrasena = '1234';

// Hashear la contraseña
$hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

// Preparar y ejecutar la consulta para insertar los datos
$sql = $conn->prepare("INSERT INTO final_administradores (nombre, email, contrasena) VALUES (?, ?, ?)");
$sql->bind_param("sss", $nombre, $email, $hashed_password);

if ($sql->execute()) {
    echo "Datos insertados correctamente.";
} else {
    echo "Error: " . $sql->error;
}

// Cerrar la consulta y la conexión
$sql->close();
$conn->close();
?>
