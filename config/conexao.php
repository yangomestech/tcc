<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "tcc";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

?>