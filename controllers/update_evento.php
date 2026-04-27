<?php
session_start();
include("../config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$id_evento = $_POST['id_evento'];
$nome = $_POST['nome'];
$detalhes = $_POST['detalhes'];
$url = $_POST['url_compra'];
$lat = $_POST['latitude_local'];
$long = $_POST['longitude_local'];
$data = $_POST['data_evento'];

/*  só atualiza se for dono */
$sql = "
UPDATE evento 
SET nome = '$nome',
    detalhes = '$detalhes',
    url_compra = '$url',
    latitude_local = '$lat',
    longitude_local = '$long',
    data_evento = '$data'
WHERE id_evento = $id_evento
AND id_produtor = $id_usuario
";

if ($conn->query($sql)) {
    header("Location: ../views/perfil.php?sucesso=evento_editado");
} else {
    echo "Erro ao atualizar evento: " . $conn->error;
}
?>