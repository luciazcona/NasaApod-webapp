<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Connect to the database
$servername = "dbserver";
$username = "grupo24";
$password = "KooPh7tohL";
$database = "db_grupo24";

$conn = new mysqli($servername, $username, $password, $database);
$conn->set_charset("utf8"); // Set character encoding to UTF-8
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}


// Query the database to get the name and email
$sql = "SELECT nombre, email
        FROM final_usuarios";

$result = $conn->query($sql);

if ($result === false) {
    die("SQL query error: " . $conn->error);
}

if ($result->num_rows > 0) {
    // Return the name and email in JSON format
    $response = array();
    while ($row = $result->fetch_assoc()) {
        $response[] = array(
            "nombre" => $row['nombre'],
            "email" => $row['email']
        );
    }
    echo json_encode($response);
} else {
    // No user found with the input name
    echo json_encode(array("error" => "No users found with the input name."));
}

$conn->close();
?> 
