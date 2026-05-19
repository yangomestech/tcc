<?php
$host = "db";
$user = "root";
$pass = "root";
$db = "tcc";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>