<?php
session_start();

// Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: views/login.php");
    exit();
}

// Controle de acesso
if (!isset($_SESSION['id_usuario'])) {
    header("Location: views/login.php");
    exit();
}

?>

<head>
    <title>Beatstreet</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<?php
require_once 'views/dashboard.php';
?>