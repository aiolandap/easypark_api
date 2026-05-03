<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "easypark";

$conn = new mysqli($host, $user, $pass, $db,3307);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$usuarioId = $_POST['usuarioId'] ?? "admin";
$placa = $_POST['placa'] ?? "";
$modelo = $_POST['modelo'] ?? "";

$data = date("Y-m-d");
$horaEntrada = date("H:i:s");
$horaSaida = null;
$tempoEstacionadoMinutos = 0;
$valorTotal = 0;
$status = "estacionado";

if ($placa == "" || $modelo == "") {
    echo "Preencha placa e modelo";
    exit;
}

$sql = "INSERT INTO veiculos 
(usuarioId, placa, modelo, data, horaEntrada, horaSaida, tempoEstacionadoMinutos, valorTotal, status)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssssids",
    $usuarioId,
    $placa,
    $modelo,
    $data,
    $horaEntrada,
    $horaSaida,
    $tempoEstacionadoMinutos,
    $valorTotal,
    $status
);

if ($stmt->execute()) {
    echo "Veículo salvo com sucesso";
} else {
    echo "Erro ao salvar veículo";
}

$stmt->close();
$conn->close();

?>