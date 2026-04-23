<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'produtor') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Criar Evento - ABase</title>

    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/criar_evento.css">
</head>

<body class="criar-evento-page">

<div class="container">

    <div class="card">

        <h2>Criar Novo Evento</h2>

        <form action="../controllers/criar_evento.php" method="POST">

            <input type="text" name="nome" placeholder="Nome do Evento" required>
            <textarea name="descricao" placeholder="Descrição do evento" required></textarea>
            <input type="datetime-local" name="data_evento" required>
            <input type="text" name="local_evento" placeholder="Local do evento" required>
            <input type="number" step="any" name="latitude" placeholder="Latitude" required>
            <input type="number" step="any" name="longitude" placeholder="Longitude" required>
            <input type="text" name="url_compra" placeholder="Link de compra do ingresso">

            <button type="submit">Criar Evento</button>

        </form>

        <br>

        <button onclick="window.location.href='perfil.php'">
            ← Voltar
        </button>

    </div>

</div>

</body>
</html>