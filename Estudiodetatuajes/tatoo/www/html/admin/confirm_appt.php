<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    require '../config.php';
    echo "Cita agregada correctamente con ID: " . $id;

    $query = "SELECT * FROM appt WHERE appt_id = '$id' && deleted=0";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        // Obtener la primera fila del resultado
        $row = $result->fetch_assoc();

        $sql = "INSERT INTO sales (create_by, tattooed_by, design, work_by, deleted, create_d, date, time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('ssssssss', $row['create_by'], $row['tattooed_by'], $row['design'], $row['work_by'], $row['deleted'], $row['create_d'], $row['date'], $row['time']);

            if ($stmt->execute()) {
                echo "Inserción exitosa en la tabla sales.";
                $sql = "UPDATE appt SET deleted = 1 WHERE appt_id= $id;";
                if ($mysqli->query($sql) === TRUE) {
                    echo "Registro eliminado correctamente";
                } else {
                    echo "Error al eliminar el registro: " . $mysqli->error;
                }
            } else {
                echo "Error al ejecutar la sentencia preparada: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error al preparar la sentencia: " . $mysqli->error;
        }
    } else {
        echo "No se encontraron filas.";
    }
} else {
    echo "Error: ID no proporcionado";
}
