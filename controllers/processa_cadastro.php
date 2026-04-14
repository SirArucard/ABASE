<?php
include("../config/config.php");

$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$tipo_usuario = $_POST['tipo_usuario'];

$sql = "INSERT INTO usuario (email, senha_hash, tipo_usuario) VALUES ('$email', '$senha', '$tipo_usuario')";

if ($conn->query($sql)) {
    echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro: " . $conn->error;
    }
?>