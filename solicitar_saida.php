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

$sql = "UPDATE veiculos 
SET status = 'aguardando_saida'
WHERE id = ? AND status = 'estacionado'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Solicitação de saída enviada para o administrador";
} else {
    echo "Erro ao solicitar saída";
}

$stmt->close();
$conn->close();

?>