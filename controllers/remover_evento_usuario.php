<?php
session_start();
include("../config/config.php");

$id_usuario = $_SESSION['id_usuario'];
$id_evento = $_GET['id_evento'];

$sql = "DELETE FROM usuario_evento 
        WHERE id_usuario = $id_usuario 
        AND id_evento = $id_evento";

$conn->query($sql);