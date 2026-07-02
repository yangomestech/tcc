<?php

require_once __DIR__ . '/env.php';

$host = $_ENV['DB_HOST'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASSWORD'] ?? '';
$db   = $_ENV['DB_NAME'] ?? '';

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    error_log("[BEATSTREET - ERRO DB] " . $e->getMessage());

    http_response_code(503);

    $isDev = ($_ENV['APP_ENV'] ?? 'production') === 'development';
    $erroSeguro = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');

    require_once __DIR__ . '/../views/erro-conexao.php';

    exit;
}
?>