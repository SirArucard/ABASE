<?php
session_start();

if ($_SESSION['tipo_usuario'] != 'produtor') {
    echo "Acesso negado";
    exit();
}
?>

<form action="../controllers/processa_evento.php" method="POST">
    <input type="text" name="nome" placeholder="Nome do evento"><br>
    <input type="date" name="data_evento"><br>
    <input type="time" name="hora_inicio"><br>
    <input type="text" name="latitude"><br>
    <input type="text" name="longitude"><br>
    <input type="text" name="url"><br>

    <button type="submit">Criar Evento</button>
</form>