<?php
require '../config.php';

// Verificar la conexión
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}
$selectedDate = $_GET['fecha'];
$tatuador = $_GET['tatuador'];




$query = "SELECT * FROM appt WHERE date = '$selectedDate' AND tattooed_by = '$tatuador'";
$result = $mysqli->query($query);


    echo "<thead class='thead-dark'><tr><th scope='col' class='text-center'> Horas disponibles del: $selectedDate</tr></thead>";
    $horasOcupadas = array();
    while ($row = $result->fetch_assoc()) {
        $horasOcupadas[] = $row['time'];
    }
    // Crear una lista de horas de 10:00 a 20:00
    $horasDisponibles = array();
    for ($hour = 10; $hour <= 20; $hour++) {
        $hora = sprintf('%02d:00:00', $hour); // Formato HH:00:00
        if (!in_array($hora, $horasOcupadas)) {
            $horasDisponibles[] = $hora;
        }
    }

    // Imprimir las horas disponibles
    foreach ($horasDisponibles as $horaDisponible) {
        echo "<tbody><tr><th scope='row' class='text-center'>$horaDisponible" . '<br>';
    }


// Cerrar la conexión
$mysqli->close();
?>