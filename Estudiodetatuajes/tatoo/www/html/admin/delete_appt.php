<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    require '../config.php';
    $sql = "DELETE FROM appt WHERE appt_id=$id;";
    if ($mysqli->query($sql) === TRUE) {
        echo "Registro eliminado correctamente";
    } else {
        echo "Error al eliminar el registro: " . $mysqli->error;
    }
    echo "Cita agregada correctamente con ID: " . $id;
} else {
    // Envía una respuesta de error si no se proporcionó un ID válido
    echo "Error: ID no proporcionado";
}
?>
