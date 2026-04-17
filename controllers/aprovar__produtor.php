<?php
session_start();
include("../config/config.php");

$id = $_GET['id'];

$conn->query("UPDATE usuario SET status_aprov = 1 WHERE id_usuario = $id");