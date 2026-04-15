<body>
    <form action="../controllers/processa_cadastro.php" method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <input type="text" name="telefone" placeholder="Telefone"><br>
        <h8>Data de Nascimento </h8><input type="date" name="data_nascimento" placeholder="Data de Nascimento">

        <h3>Endereço (Opcional)</h3>
        <input type="text" name="rua" placeholder="Rua">
        <input type="text" name="numero" placeholder="Número">
        <input type="text" name="complemento" placeholder="Complemento">
        <input type="text" name="cep" placeholder="CEP">
        <input type="text" name="bairro" placeholder="Bairro">
        <input type="text" name="cidade" placeholder="Cidade">

        <select name="tipo" id="tipo" onchange="mostrarCampos()" required>
            <option value="">Selecione</option>
            <option value="cliente">Cliente</option>
            <option value="produtor">Produtor</option>
        </select><br>

        <div id="campo_extra"></div>

        <button type="submit">Cadastrar</button>

    </form>
    <script>
        function mostrarCampos() {

            let tipo = document.getElementById("tipo").value;
            let div = document.getElementById("campo_extra");

            if (tipo === "cliente") {
                div.innerHTML = "<input type='text' name='cpf' placeholder='CPF' required>";
            } else if (tipo === "produtor") {
                div.innerHTML = `
            <input type='text' name='cnpj' placeholder='CNPJ' required><br>
            <input type='text' name='razao_social' placeholder='Razão Social'><br>
            <input type='text' name='nome_fantasia' placeholder='Nome Fantasia'>
        `;
            } else {
                div.innerHTML = "";
            }
        }
    </script>
</body>