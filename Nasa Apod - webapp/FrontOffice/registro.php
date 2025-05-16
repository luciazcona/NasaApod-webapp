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
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptar la contraseña

// Verificar si los datos fueron recibidos
if (empty($nombre) || empty($email) || empty($contrasena)) {
    echo json_encode(array("success" => false, "message" => "Please input a name, email and password."));
    exit();
}

// Verificar si el correo electrónico ya esta registrado
$sql = "SELECT * FROM final_usuarios WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // El correo electrónico ya esta registrado
    echo json_encode(array("success" => false, "message" => "Email already registered."));
} else {
    // Insertar datos en la tabla usuarios
    $sql_insert = "INSERT INTO final_usuarios (nombre, email, contrasena) VALUES ('$nombre', '$email', '$contrasena')";

    if ($conn->query($sql_insert) === TRUE) {
        echo json_encode(array("success" => true, "message" => "User registered correctly."));
    } else {
        echo json_encode(array("success" => false, "message" => "Error: " . $sql_insert . "<br>" . $conn->error));
    }
}

// Cerrar conexión
$conn->close();
?>