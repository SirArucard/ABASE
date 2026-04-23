<?php
session_start();
include("../config/config.php");

if ($_SESSION['tipo_usuario'] != 'admin') {
    exit("Acesso negado");
}

$id = $_GET['id'];

$sql = "UPDATE usuario SET status_aprov = 1 WHERE id_usuario = $id";

$conn->query($sql);