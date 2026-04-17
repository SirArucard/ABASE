<?php
include("../config/config.php");

// SEGURANÇA BÁSICA
$email = trim($_POST['email']);
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$tipo = $_POST['tipo'];

$telefone = $_POST['telefone'] ?? null;
$data_nascimento = $_POST['data_nascimento'] ?? null;

$rua = $_POST['rua'] ?? null;
$numero = $_POST['numero'] ?? null;
$complemento = $_POST['complemento'] ?? null;
$cep = $_POST['cep'] ?? null;
$bairro = $_POST['bairro'] ?? null;
$cidade = $_POST['cidade'] ?? null;

/* =========================
   VALIDAÇÕES BACK-END
========================= */

// EMAIL duplicado
$sqlCheckEmail = "SELECT id_usuario FROM usuario WHERE email = '$email'";
$resultEmail = $conn->query($sqlCheckEmail);

if ($resultEmail->num_rows > 0) {
    header("Location: ../index.php?erro=email");
    exit();
}

// CPF
if ($tipo == 'cliente') {
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);

    $sqlCheckCpf = "SELECT id_usuario FROM usuario_cliente WHERE cpf = '$cpf'";
    $resultCpf = $conn->query($sqlCheckCpf);

    if ($resultCpf->num_rows > 0) {
        header("Location: ../index.php?erro=cpf");
        exit();
    }
}

// CNPJ
if ($tipo == 'produtor') {
    $cnpj = preg_replace('/\D/', '', $_POST['cnpj']);

    $sqlCheckCnpj = "SELECT id_usuario FROM usuario_produtor WHERE cnpj = '$cnpj'";
    $resultCnpj = $conn->query($sqlCheckCnpj);

    if ($resultCnpj->num_rows > 0) {
        header("Location: ../index.php?erro=cnpj");
        exit();
    }
}

/* =========================
   INSERT USUARIO
========================= */

$sql = "INSERT INTO usuario 
(email, senha_hash, tipo_usuario, telefone, data_nascimento, rua, numero, complemento, cep, bairro, cidade)
VALUES 
('$email', '$senha', '$tipo', '$telefone', '$data_nascimento', '$rua', '$numero', '$complemento', '$cep', '$bairro', '$cidade')";

if ($conn->query($sql)) {

    $id_usuario = $conn->insert_id;

    // CLIENTE
    if ($tipo == 'cliente') {
        $sql = "INSERT INTO usuario_cliente (id_usuario, cpf)
        VALUES ($id_usuario, '$cpf')";
        $conn->query($sql);
    }

    // PRODUTOR
    if ($tipo == 'produtor') {
        $sql = "INSERT INTO usuario_produtor (id_usuario, cnpj, razao_social, nome_fantasia)
        VALUES ($id_usuario, '$cnpj', '{$_POST['razao_social']}', '{$_POST['nome_fantasia']}')";
        $conn->query($sql);
    }

    header("Location: ../index.php?sucesso=cadastro");
    exit();

} else {
    header("Location: ../index.php?erro=geral");
    exit();
}
?>