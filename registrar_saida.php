<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "easypark";

$conn = new mysqli($host, $user, $pass, $db, 3307);

if ($conn->connect_error) {
    die("Erro de conexão");
}

$id = $_POST['id'] ?? "";

if ($id == "") {
    echo "ID do veículo não informado";
    exit;
}

$sql = "SELECT horaEntrada, data FROM veiculos WHERE id = ? AND status = 'estacionado'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Veículo não encontrado";
    exit;
}

$veiculo = $result->fetch_assoc();

$entrada = strtotime($veiculo['data'] . " " . $veiculo['horaEntrada']);
$saida = time();

$tempoSegundos = $saida - $entrada;

if ($tempoSegundos < 1) {
    $tempoSegundos = 1;
}

$tempoMinutos = ceil($tempoSegundos / 60);

$config = $conn->query("SELECT valorPorHora FROM configuracao LIMIT 1");

if (!$config || $config->num_rows == 0) {
    echo "Erro: valor por hora não configurado";
    exit;
}

$configRow = $config->fetch_assoc();

$valorPorHora = floatval($configRow['valorPorHora']);

if ($valorPorHora <= 0) {
    echo "Erro: valor por hora inválido";
    exit;
}

$valorTotal = ($tempoSegundos / 3600) * $valorPorHora;

$horaSaida = date("H:i:s");

$update = "UPDATE veiculos 
SET horaSaida = ?, 
tempoEstacionadoMinutos = ?, 
valorTotal = ?, 
status = 'finalizado'
WHERE id = ?";

$stmtUpdate = $conn->prepare($update);
$stmtUpdate->bind_param("sidi", $horaSaida, $tempoMinutos, $valorTotal, $id);

if ($stmtUpdate->execute()) {
    echo "Saída registrada\n";
    echo "Tempo estacionado: " . $tempoMinutos . " minutos\n";
    echo "Valor total: R$ " . number_format($valorTotal, 2, ',', '.');
} else {
    echo "Erro ao registrar saída";
}

$stmt->close();
$stmtUpdate->close();
$conn->close();

?>