<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastro_usuario.php');
    exit;
}

include "conexao.php";

$username = $_POST['username'];
$nome = $_POST['nome_usuario'];
$email = $_POST['email_usuario'];
$senha = $_POST['senha_usuario'];
$confirmar = $_POST['confirmar_senha'];

if ($senha != $confirmar) {
    echo "As senhas não coincidem";
    exit();
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuario 
(username, nome_usuario, email_usuario, senha_usuario)
VALUES 
('$username','$nome','$email','$senhaHash')";

try {
    if ($conn->query($sql) === TRUE) {
        echo "Cadastro realizado com sucesso!<br>";
        echo "<a href='login_usuario.php'>Ir para login</a>";
    }
} catch (Exception $e) {
    echo "Erro real: " . $e->getMessage();
}
?>