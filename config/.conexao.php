<?php

require_once 'env.php';

$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASSWORD'];
$db   = $_ENV['DB_NAME'];

try {
    // Cria a conexão usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    
    // Configura o PDO para disparar exceções (Isso faz o bloco try/catch do cadastro funcionar perfeitamente)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Se der erro, para o sistema e avisa
    die("Erro de conexão. A cena está temporariamente fora do ar. :( " . $e->getMessage());
}
?>