<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "easypark";

$conn = new mysqli($host, $user, $pass, $db,3307);

if ($conn->connect_error) {
    die("Erro de conexão");
}

$config = $conn->query("SELECT totalVagas FROM configuracao WHERE id = 1");
$configRow = $config->fetch_assoc();

$totalVagas = intval($configRow["totalVagas"]);

$ocupadasQuery = $conn->query("SELECT COUNT(*) AS ocupadas FROM veiculos WHERE status = 'estacionado'");
$ocupadasRow = $ocupadasQuery->fetch_assoc();

$vagasOcupadas = intval($ocupadasRow["ocupadas"]);
$vagasLivres = $totalVagas - $vagasOcupadas;

echo json_encode([
    "totalVagas" => $totalVagas,
    "vagasOcupadas" => $vagasOcupadas,
    "vagasLivres" => $vagasLivres
]);

$conn->close();

?>