<?php
include("../config/config.php");

$lat_user = $_GET['lat'];
$long_user = $_GET['long'];
$raio = $_GET['raio'];

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
 HAVING distancia < $raio
 ORDER BY distancia ASC
 ";

$result = $conn->query($sql);

$eventos = [];

while($row = $result->fetch_assoc()) {
    $eventos[] = $row;
}

echo json_encode($eventos);
?>