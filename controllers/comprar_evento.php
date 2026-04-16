<?php
session_start();
include("../config/config.php");

$id_usuario = $_SESSION['id_usuario'];
$id_evento = $_GET['id_evento'];

// verifica se já existe o evento no perfil do usuário
$sqlCheck = "SELECT * FROM usuario_evento 
             WHERE id_usuario = $id_usuario 
             AND id_evento = $id_evento";

$resultCheck = $conn->query($sqlCheck);

if ($resultCheck->num_rows > 0) {
    echo "Evento já adicionado!";
    exit();
}

// se não existir, insere o evento no perfil do usuário
$sqlInsert = "INSERT INTO usuario_evento (id_usuario, id_evento)
              VALUES ($id_usuario, $id_evento)";

$conn->query($sqlInsert);

echo "Evento adicionado com sucesso!";