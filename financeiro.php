<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "easypark";

$conn = new mysqli($host, $user, $pass, $db, 3307);

if ($conn->connect_error) {
    die("Erro de conexão");
}

$sql = "SELECT 
    COUNT(*) as totalSaidas,
    SUM(valorTotal) as totalRecebido,
    AVG(valorTotal) as ticketMedio
FROM veiculos
WHERE status = 'finalizado'";

$result = $conn->query($sql);

$data = $result->fetch_assoc();

echo json_encode([
    "totalSaidas" => intval($data['totalSaidas']),
    "totalRecebido" => floatval($data['totalRecebido']),
    "ticketMedio" => floatval($data['ticketMedio'])
]);

$conn->close();

?>