<?php
session_start();

// Roteamento de logout super simples e direto
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: views/login.php");
    exit();
}

// Verifica controle de acesso
if (!isset($_SESSION['id_usuario'])) {
    header("Location: views/login.php");
    exit();
} else {
    // Carrega a view principal do sistema protegido
    require_once 'views/dashboard.php';
}
?>
