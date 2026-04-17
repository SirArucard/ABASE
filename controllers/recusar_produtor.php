<?php
session_start();
include("../config/config.php");

$id = $_GET['id'];

$conn->query("DELETE FROM usuario WHERE id_usuario = $id");