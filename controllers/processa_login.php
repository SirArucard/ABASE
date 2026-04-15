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

        if ($user['tipo_usuario'] == 'produtor' && $user['status_aprov'] == 0) {
            echo "Produtor ainda não aprovado pelo administrador!";
            exit();
        }

        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

        header("Location: ../views/dashboard.php");
        exit();

    } else {
        echo "Senha incorreta!";
    }

} else {
    echo "Usuário não encontrado!";
}
?>