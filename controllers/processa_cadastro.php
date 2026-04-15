<?php
include("../config/config.php");

$email = $_POST['email'];
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

// INSERT USUARIO
$sql = "INSERT INTO usuario 
(email, senha_hash, tipo_usuario, telefone, data_nascimento, rua, numero, complemento, cep, bairro, cidade)
VALUES 
('$email', '$senha', '$tipo', '$telefone', '$data_nascimento', '$rua', '$numero', '$complemento', '$cep', '$bairro', '$cidade')";

if ($conn->query($sql)) {

    $id_usuario = $conn->insert_id;

    // CLIENTE
    if ($tipo == 'cliente') {

        $cpf = $_POST['cpf'];

        $sql = "INSERT INTO usuario_cliente (id_usuario, cpf)
        VALUES ($id_usuario, '$cpf')";

        $conn->query($sql);
    }

    // PRODUTOR
    if ($tipo == 'produtor') {

        $cnpj = $_POST['cnpj'];
        $razao_social = $_POST['razao_social'] ?? null;
        $nome_fantasia = $_POST['nome_fantasia'] ?? null;

        $sql = "INSERT INTO usuario_produtor (id_usuario, cnpj, razao_social, nome_fantasia)
        VALUES ($id_usuario, '$cnpj', '$razao_social', '$nome_fantasia')";

        $conn->query($sql);
    }

    echo "Cadastro realizado com sucesso!";

} else {
    echo "Erro ao cadastrar: " . $conn->error;
}
?>