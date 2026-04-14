<?php
session_start();
include("../config/config.php");

$id_produtor = $_SESSION['id_usuario'];

$nome = $_POST['nome'];
$data = $_POST['data_evento'];
$hora = $_POST['hora_inicio'];
$lat = $_POST['latitude'];
$long = $_POST['longitude'];
$url = $_POST['url'];

$sql = "INSERT INTO evento 
(nome, data_evento, hora_inicio, latitude_local, longitude_local, url_compra, id_produtor)
VALUES 
('$nome','$data','$hora','$lat','$long','$url','$id_produtor')";

if ($conn->query($sql)) {
    echo "Evento criado!";
} else {
    echo "Erro: " . $conn->error;
}
?>