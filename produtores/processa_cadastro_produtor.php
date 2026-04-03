<?php
include __DIR__ . '/../config/conexao.php';

// pegar dados
$username = trim($_POST['username']);
$nome = trim($_POST['nome_produtor']);
$cpf = trim($_POST['CPF_produtor']);
$rg = trim($_POST['RG_produtor']);
$email = trim($_POST['email_produtor']);
$telefone = trim($_POST['telefone_produtor']);
$senha = trim($_POST['senha_produtor']);
$confirmar = trim($_POST['confirmar_senha']);

// validar senha
if ($senha != $confirmar) {
    echo "As senhas não coincidem";
    exit();
}

// criptografar senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);


// inserir no banco
$sql = "INSERT INTO produtor 
(nome_produtor, CPF_produtor, RG_produtor, email_produtor, telefone_produtor, senha_produtor, username) 
VALUES 
('$nome','$cpf','$rg','$email','$telefone','$senha_hash','$username')";

echo "SQL gerado:<br>";
echo $sql . "<br><br>";

$resultado = $conn->query($sql);
var_dump($resultado);
echo "<br>";
echo "Erro do MySQL: " . $conn->error;

if ($resultado === TRUE) {
    header("Location: dashboard.php");
} else {
    echo "Erro MySQL: " . $conn->error;
}
?>