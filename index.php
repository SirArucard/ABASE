<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>ABase</title>

    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body class="login-page">
<div class="container">

    <img src="assets/img/LOGO1.png" class="logo">
    <h1>ABase</h1>
    <p>Ache os eventos mais próximos de você e aproveite ao máximo sua experiência!</p>

    <!-- LOGIN -->
    <div id="loginForm">
        <form action="/abase/controllers/processa_login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>

        <div class="switch" onclick="toggleForm()">Não tem conta? Cadastre-se</div>
    </div>

    <!-- CADASTRO -->
    <div id="cadastroForm" class="hidden">
        <form action="/abase/controllers/processa_cadastro.php" method="POST" onsubmit="return validarFormulario()">

            <select name="tipo" id="tipo" onchange="mostrarCampos()" required>
                <option value="">Selecione o tipo</option>
                <option value="cliente">Cliente</option>
                <option value="produtor">Produtor</option>
            </select>

            <input type="email" name="email" placeholder="Email" required>
            <input type="password" id="senha" name="senha" placeholder="Senha" required>
            <input type="text" id="telefone" name="telefone" placeholder="Telefone" maxlength="11">
            <input type="date" name="data_nascimento">

            <div id="campo_extra"></div>

            <!-- ENDEREÇO -->
            <h4>Endereço (opcional)</h4>
            <button type="button" onclick="toggleEndereco()">+ Adicionar endereço</button>

            <div id="endereco" class="hidden">
                <input type="text" name="rua" placeholder="Rua">
                <input type="text" name="numero" placeholder="Número">
                <input type="text" name="complemento" placeholder="Complemento">
                <input type="text" name="cep" placeholder="CEP">
                <input type="text" name="bairro" placeholder="Bairro">
                <input type="text" name="cidade" placeholder="Cidade">
            </div>

            <button type="submit">Cadastrar</button>
        </form>

        <div class="switch" onclick="toggleForm()">Já tem conta? Login</div>
    </div>

</div>

<!-- MODAL -->
<div id="modal" class="modal hidden">
    <div class="modal-content">
        <p id="modal-text"></p>
        <button onclick="fecharModal()">OK</button>
    </div>
</div>

<script>

// ================= ENDEREÇO =================
function toggleEndereco() {
    document.getElementById("endereco").classList.toggle("hidden");
}

// ================= MODAL =================
function abrirModal(msg) {
    document.getElementById("modal-text").innerText = msg;
    document.getElementById("modal").classList.remove("hidden");
}

function fecharModal() {
    document.getElementById("modal").classList.add("hidden");
}

// ================= TROCAR FORM =================
function toggleForm() {
    document.getElementById("loginForm").classList.toggle("hidden");
    document.getElementById("cadastroForm").classList.toggle("hidden");
}

// ================= CAMPOS DINÂMICOS =================
function mostrarCampos() {
    let tipo = document.getElementById("tipo").value;
    let div = document.getElementById("campo_extra");

    if (tipo === "cliente") {
        div.innerHTML = "<input type='text' name='cpf' placeholder='CPF' required>";
    } else if (tipo === "produtor") {
        div.innerHTML = `
            <input type='text' name='cnpj' placeholder='CNPJ' required>
            <input type='text' name='razao_social' placeholder='Razão Social'>
            <input type='text' name='nome_fantasia' placeholder='Nome Fantasia'>
        `;
    } else {
        div.innerHTML = "";
    }
}

// ================= VALIDAÇÕES =================
function validarSenha(senha) {
    return senha.length >= 8 &&
        /[A-Za-z]/.test(senha) &&
        /\d/.test(senha);
}

function validarTelefone(tel) {
    return tel === "" || /^\d{11}$/.test(tel);
}

function validarDataNascimento() {
    let data = document.querySelector("[name='data_nascimento']").value;

    if (!data) return true;

    return new Date(data) <= new Date();
}

function validarCPF(cpf) {
    return /^\d{11}$/.test(cpf.replace(/\D/g, ''));
}

function validarCNPJ(cnpj) {
    return /^\d{14}$/.test(cnpj.replace(/\D/g, ''));
}

function validarFormulario() {

    let tipo = document.getElementById("tipo").value;
    let senha = document.getElementById("senha").value;
    let telefone = document.getElementById("telefone").value;

    if (!validarSenha(senha)) {
        abrirModal("Senha deve ter no mínimo 8 caracteres com letra e número");
        return false;
    }

    if (!validarTelefone(telefone)) {
        abrirModal("Telefone deve conter DDD + 9 dígitos (11 números)");
        return false;
    }

    if (!validarDataNascimento()) {
        abrirModal("Data de nascimento inválida");
        return false;
    }

    if (tipo === "cliente") {
        let cpf = document.querySelector("[name='cpf']").value;
        if (!validarCPF(cpf)) {
            abrirModal("CPF inválido (11 números)");
            return false;
        }
    }

    if (tipo === "produtor") {
        let cnpj = document.querySelector("[name='cnpj']").value;
        if (!validarCNPJ(cnpj)) {
            abrirModal("CNPJ inválido (14 números)");
            return false;
        }
    }

    return true;
}

// ================= URL PARAMS =================
const params = new URLSearchParams(window.location.search);

const erro = params.get("erro");
const sucesso = params.get("sucesso");

if (erro === "usuario") abrirModal("❌ Usuário não encontrado");
if (erro === "senha") abrirModal("❌ Senha incorreta");
if (erro === "email") abrirModal("❌ Email já cadastrado");
if (erro === "cpf") abrirModal("❌ CPF já cadastrado");
if (erro === "cnpj") abrirModal("❌ CNPJ já cadastrado");
if (erro === "geral") abrirModal("❌ Erro ao cadastrar");

if (sucesso === "cadastro") abrirModal("✅ Cadastro realizado com sucesso!");

</script>

</body>
</html>