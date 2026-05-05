<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "easypark";

$conn = new mysqli($host, $user, $pass, $db, 3307);

if ($conn->connect_error) {
    die("Erro de conexão");
}

$sql = "SELECT id, placa, modelo, status 
FROM veiculos 
WHERE status = 'estacionado' OR status = 'aguardando_saida'";

$result = $conn->query($sql);

$veiculos = array();

while ($row = $result->fetch_assoc()) {
    $veiculos[] = $row;
}

echo json_encode($veiculos);

$conn->close();

?>