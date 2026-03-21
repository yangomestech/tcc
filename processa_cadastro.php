<?php
include "conexao.php";

$username = $_POST['username'];
$nome = $_POST['nome_usuario'];
$email = $_POST['email_usuario'];
$telefone = $_POST['telefone_usuario'];
$data_nascimento = $_POST['data_nascimento'];

$senha = password_hash($_POST['senha_usuario'], PASSWORD_DEFAULT);

$sql = "INSERT INTO usuario 
(username, nome_usuario, email_usuario, telefone_usuario, data_nascimento, senha_usuario)
VALUES 
('$username','$nome','$email','$telefone','$data_nascimento','$senha')";

if ($conn->query($sql) === TRUE) {
    echo "Cadastro realizado com sucesso!<br>";
    echo "<a href='login.php'>Ir para login</a>";
} else {
    echo "Erro: " . $conn->error;
}
?>