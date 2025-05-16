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
$email = $_POST['email'];
$contrasena = $_POST['contrasena'];

// Verificar si los datos fueron recibidos
if (empty($email) || empty($contrasena)) {
    echo json_encode(array("success" => false, "message" => "Please input an email and password."));
    exit();
}

// Preparar y ejecutar la consulta para obtener el usuario por nombre
$sql = $conn->prepare("SELECT contrasena FROM final_administradores WHERE email = ?");
$sql->bind_param("s", $email);
$sql->execute();
$result = $sql->get_result();

// Verificar si la consulta fue ejecutada correctamente
if ($result === false) {
    echo json_encode(array("success" => false, "message" => "Error on SQL request: " . $conn->error));
    $sql->close();
    $conn->close();
    exit();
}

if ($result->num_rows > 0) {
    // Obtener la fila de resultados
    $row = $result->fetch_assoc();
    $hashed_password = $row['contrasena'];

    // Verificar la contraseña
    if (password_verify($contrasena, $hashed_password)) {
        echo json_encode(array("success" => true, "message" => "Login successful."));
    } else {
        echo json_encode(array("success" => false, "message" => "Incorrect password."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Email not registered."));
}

// Cerrar la consulta y la conexión
$sql->close();
$conn->close();
?>
