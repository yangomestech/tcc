<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: '/../views/login.php");
    exit();
}
$username = $_SESSION['username'] ?? 'Usuário';

// Lógica para pegar as iniciais do usuário
$words = explode(" ", trim($username));
$initials = "";
if (count($words) >= 2) {
    $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
} else {
    $initials = strtoupper(substr($words[0], 0, 2));
}

// Fallback caso o e-mail não esteja na sessão
$email = $_SESSION['email_usuario'] ?? 'usuario@beatstreet.com';

require_once __DIR__ . '/../views/dashboard.php';
?>

