<?php
session_start();
include("../config/config.php");

// pegar dados
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// valida vazio
if (empty($email) || empty($senha)) {
    header("Location: ../index.php?erro=geral");
    exit();
}

// evita SQL injection
$email = $conn->real_escape_string($email);

// busca usuário
$sql = "SELECT * FROM usuario WHERE email = '$email'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {

    $user = $result->fetch_assoc();

    // verifica senha
    if (password_verify($senha, $user['senha_hash'])) {

        // 🚫 produtor não aprovado
        if ($user['tipo_usuario'] == 'produtor' && $user['status_aprov'] == 0) {
            header("Location: ../index.php?erro=aprovacao");
            exit();
        }

        // ✅ cria sessão
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

        // redirecionamento por tipo
        if ($user['tipo_usuario'] == 'admin') {
            header("Location: ../views/admin.php");
        } else {
            header("Location: ../views/perfil.php");
        }
        exit();
    } else {
        header("Location: ../index.php?erro=senha");
        exit();
    }
} else {
    header("Location: ../index.php?erro=usuario");
    exit();
}
