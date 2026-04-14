<?php
session_start();
include("../config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    echo "Você precisa estar logado para fazer check-in!";
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_evento = $_GET['id_evento'];
$nota = $_GET['nota'];
$comentario = $_GET['comentario'];

//verifica se fez check-in
$sql = "SELECT id_checkin FROM checkin
WHERE id_usuario = $id_usuario AND id_evento = $id_evento";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Você precisa fazer check-in para avaliar o evento!";
    exit();
}

$checkin = $result->fetch_assoc();
$id_checkin = $checkin['id_checkin'];

//salva avaliação
$sql = "INSERT INTO avaliacao (nota, comentario, data_avaliacao, id_checkin)
VALUES ($nota, '$comentario', NOW(), $id_checkin)";

if ($conn->query($sql)) {
    echo "Avaliação salva com sucesso!";
} else {
    echo "Erro ao avaliar.";
}
?>