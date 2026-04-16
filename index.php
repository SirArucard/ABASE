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
        <img src="assets/img/LOGO1.png" alt="ABase Logo" class="logo">
        <h1>ABase</h1>
        <p>Onde todo rolê começa! Descubra eventos próximos e compre ingressos facilmente.</p>

        <!--Tela Login-->
        <div id="loginForm">
            <form action="/abase/controllers/processa_login.php" method="POST">
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="senha" placeholder="Senha" required><br>
                <button type="submit">Entrar</button>
            </form>
            <div class="switch" onclick="toggleForm()">Não tem conta? Cadastre-se</div>
        </div>
        <!--Tela Cadastro-->
        <div id="cadastroForm" class="hidden">
            <form action="/abase/controllers/processa_cadastro.php" method="POST">
                <select name="tipo" id="tipo" onchange="mostrarCampos()" required>
                    <option value="">Selecione o tipo de usuário</option>
                    <option value="cliente">Cliente</option>
                    <option value="produtor">Produtor</option>
                </select>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <input type="text" name="telefone" placeholder="Telefone">
                <input type="date" name="data_nascimento" placeholder="Data de Nascimento">
                <div id="campo_extra"></div>

                <h4> Endereço </h4>
                <input type="text" name="rua" placeholder="Rua">
                <input type="text" name="numero" placeholder="Número">
                <input type="text" name="complemento" placeholder="Complemento">
                <input type="text" name="cep" placeholder="CEP">
                <input type="text" name="bairro" placeholder="Bairro">
                <input type="text" name="cidade" placeholder="Cidade">

                <button type="submit">Cadastrar</button>
            </form>

            <div class="switch" onclick="toggleForm()">Já tem conta? Faça seu login!!</div>
        </div>
    </div>
    <script>
        //alternar login/cadastro
        function toggleForm() {
            document.getElementById("loginForm").classList.toggle("hidden");
            document.getElementById("cadastroForm").classList.toggle("hidden");
        }

        //dinamismo dos campos
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
    </script>
</body>

</html>