<?php
session_start();
include("../config/config.php");

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuario WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($senha, $user['senha_hash'])) {
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

        header("Location: ../views/dashboard.php");

    } else {
        echo "Senha incorreta!";
    }
} else {
    echo "Usuário não encontrado!";

}