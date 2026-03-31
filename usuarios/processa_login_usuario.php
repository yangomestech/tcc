<?php
session_start();
include __DIR__ . '/../config/conexao.php';

$username = $_POST['username'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuario WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $usuario = $result->fetch_assoc();

    if (password_verify($senha, $usuario['senha_usuario'])) {

        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['username'] = $usuario['username'];

        header("Location: dashboard.php");
        exit();

    } else {
        echo "Senha incorreta";
    }

} else {
    echo "Usuário não encontrado";
}
?>