<?php
include("../config/config.php");

// se ainda não tem localização → pede
if (!isset($_GET['lat']) || !isset($_GET['long'])) {
?>
    <script>
        navigator.geolocation.getCurrentPosition(function(position) {
            let lat = position.coords.latitude;
            let long = position.coords.longitude;

            window.location.href = "listar_eventos.php?lat=" + lat + "&long=" + long;
        });
    </script>
<?php
    exit();
}

// recebe localização
$lat_user = $_GET['lat'];
$long_user = $_GET['long'];

// QUERY Haversine
$sql = "
SELECT *,
(6371 * ACOS(
    COS(RADIANS($lat_user)) *
    COS(RADIANS(latitude_local)) *
    COS(RADIANS(longitude_local) - RADIANS($long_user)) +
    SIN(RADIANS($lat_user)) *
    SIN(RADIANS(latitude_local))
)) AS distancia
FROM evento
HAVING distancia < 1000
ORDER BY distancia ASC
";

$result = $conn->query($sql);

// mostra resultados
while($row = $result->fetch_assoc()) {
    echo "Evento: " . $row['nome'] . "<br>";
    echo "Distância: " . round($row['distancia'], 2) . " km<br><br>";
}
?>