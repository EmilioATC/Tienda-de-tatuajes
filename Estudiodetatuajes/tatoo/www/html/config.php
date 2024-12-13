<?php
$mysqli = new mysqli('192.168.0.5', 'programador', 'Pr0gr4m4d0r', 'supernova');

if ($mysqli->connect_error) {
    die('Error de conexión a la base de datos: ' . $mysqli->connect_error);
}
?>