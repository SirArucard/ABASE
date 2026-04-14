<?php
include("../config/config.php");

$id = $_GET['id'];

$sql = "DELETE FROM evento WHERE id_evento = $id";

if ($conn->query($sql)) {
    echo "Evento deletado!";
} else {
    echo "Erro";
}
?>