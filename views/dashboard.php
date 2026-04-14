<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

echo "Usuário logado! <br>";
echo "Tipo: " . $_SESSION['tipo_usuario'];
?>

<br>
<a href="criar_evento.php">Criar Evento</a><br>
<a href="listar_eventos.php">Meus Eventos</a><br>
<a href="../controllers/logout.php">Sair</a>