<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>ABase</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('assets/img/background_saturn.jpg') no-repeat center center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .logo {
            width: 120px;
            margin-bottom: 10px;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
        }

        .container {
            animation: fadeIn 0.8s ease-in-out;
            background: rgba(2, 6, 23, 0.8);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 25px;
            width: 350px;
            text-align: center;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);

        }

        .container:hover {
            transform: scale(1.01);
            transition: 0.3s;
        }


        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            color: #cbd5f5;
        }

        input:focus,
        select:focus {
            box-shadow: 0 0 5px #22c55e;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 8px;
            border: none;
            outline: none;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(45deg, #22c55e, #16a34a);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            transform: scale(1.05);
        }

        .switch {
            margin-top: 15px;
            cursor: pointer;
            color: #38bdf8;
        }

        .hidden {
            display: none;
        }
    </style>

</head>

<body>
    <div class="container">
        <img src="assets/img/LOGO1.png" alt="ABase Logo" class="logo">
        <h1>ABase</h1>
        <p>Onde todo rolê começa! Descubra eventos próximos e compre ingressos facilmente.</p>

        <!--Tela Login-->
        <div id="loginForm">
            <form action="../controllers/processa_login.php" method="POST">
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="senha" placeholder="Senha" required><br>
                <button type="submit">Entrar</button>
            </form>
            <div class="switch" onclick="toggleForm()">Não tem conta? Cadastre-se</div>
        </div>
        <!--Tela Cadastro-->
        <div id="cadastroForm" class="hidden">
            <form action="../controllers/processa_cadastro.php" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <select name="tipo" id="tipo" onchange="mostrarCampos()" required>
                    <option value="">Selecione o tipo de usuário</option>
                    <option value="cliente">Cliente</option>
                    <option value="produtor">Produtor</option>
                </select>
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