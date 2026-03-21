<?php
session_start();

if(!isset($_SESSION['id_usuario'])){
    header("Location: login.php");
    exit();
}
?>

<h1>Bem-vindo <?php echo $_SESSION['username']; ?></h1>

<a href="logout.php">Sair</a>