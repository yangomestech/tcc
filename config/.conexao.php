<?php

require_once __DIR__ . '/env.php';

$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASSWORD'];
$db   = $_ENV['DB_NAME'];

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {

    die("Erro de conexão. A cena está temporariamente fora do ar. :( " . $e->getMessage());
}
?>