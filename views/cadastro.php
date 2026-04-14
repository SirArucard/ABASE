<form action="processa_cadastro.php" method="post">
    <input type="email" name="email" placeholder="E-Mail" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>

    <select name="tipo_usuario">
        <option value="cliente">Cliente</option>
        <option value="produtor">Produtor</option>
    </select><br>
    <button type="submit">Cadastrar</button>
</form>