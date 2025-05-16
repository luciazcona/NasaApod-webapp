<?php
// Configuración de la conexión a la base de datos
$servername = "dbserver";
$username = "grupo24";
$password = "KooPh7tohL";
$database = "db_grupo24";

// Conexión a la base de datos
$dblink = mysqli_connect($servername, $username, $password, $database);

// Obtener todas las tablas en la base de datos
$tables_query = "SHOW TABLES";
if ($tables_result = mysqli_query($dblink, $tables_query)) {
	echo "Tablas obtenidas.<br>";
} else {
	echo "Error al obtener las tablas: " . mysqli_error($dblink);
}

// Desactivar restricciones de clave externa
$sql = "SET FOREIGN_KEY_CHECKS=0";
mysqli_query($dblink, $sql);

if ($tables_result->num_rows > 0) {
    while ($row = $tables_result->fetch_assoc()) {
        $table_name = $row["Tables_in_" . $database];
        
        // Truncar la tabla para eliminar todos los datos
        $truncate_query = "TRUNCATE TABLE $table_name";
        if (mysqli_query($dblink, $truncate_query)) {
			echo "<br>Tabla $table_name limpiada.<br>";
		} else {
			echo "Error al limpiar los datos: " . mysqli_error($dblink);
		}
        
        // Reiniciar el valor autoincremental de la columna ID
        $reset_query = "ALTER TABLE $table_name AUTO_INCREMENT = 1";
        if (mysqli_query($dblink, $reset_query)) {
			echo "Valor autoincremental de $table_name reiniciado.<br>";
		} else {
			echo "Error al reiniciar la columna ID: " . mysqli_error($dblink);
		}
    }
} else {
    echo "No se encontraron tablas en la base de datos.";
}

// Activar restricciones de clave externa
$sql = "SET FOREIGN_KEY_CHECKS=1";
mysqli_query($dblink, $sql);

// Cerrar conexión
mysqli_close($dblink);
?>
