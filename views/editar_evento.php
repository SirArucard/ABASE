<?php
session_start();
include("../config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_evento = $_GET['id'] ?? null;

if (!$id_evento) {
    echo "Evento inválido.";
    exit();
}

/* 🔒 GARANTE QUE O EVENTO É DO PRODUTOR */
$sql = "SELECT * FROM evento WHERE id_evento = $id_evento AND id_produtor = $id_usuario";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Evento não encontrado ou acesso negado.";
    exit();
}

$evento = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Editar Evento</title>

    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body class="login-page">

    <div class="container">

        <h2>Editar Evento</h2>

        <form action="../controllers/update_evento.php" method="POST">

            <input type="hidden" name="id_evento" value="<?= $evento['id_evento'] ?>">

            <input type="text" name="nome" value="<?= $evento['nome'] ?>" placeholder="Nome do evento" required>

            <textarea name="detalhes" placeholder="Detalhes do evento"><?= $evento['detalhes'] ?></textarea>

            <input type="text" name="url_compra" value="<?= $evento['url_compra'] ?>" placeholder="URL do ingresso">

            <input type="text" name="latitude_local" value="<?= $evento['latitude_local'] ?>" placeholder="Latitude">

            <input type="text" name="longitude_local" value="<?= $evento['longitude_local'] ?>" placeholder="Longitude">

            <input type="date" name="data_evento" value="<?= $evento['data_evento'] ?>">

            <button type="submit" class="btn-primary btn-full">
                Salvar Alterações
            </button>

        </form>

        <button onclick="window.location.href='perfil.php'" class="btn-full">
            Voltar
        </button>

    </div>

</body>

</html>