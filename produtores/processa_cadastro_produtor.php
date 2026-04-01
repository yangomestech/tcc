<?php
include __DIR__ . '/../config/conexao.php';

// pegar dados
$nome = $_POST['nome_produtor'];
$cpf = $_POST['CPF_produtor'];
$rg = $_POST['RG_produtor'];
$email = $_POST['email_produtor'];
$telefone = $_POST['telefone_produtor'];
$senha = $_POST['senha_produtor'];
$confirmar = $_POST['confirmar_senha'];

// validar senha
if ($senha != $confirmar) {
    echo "As senhas não coincidem";
    exit();
}

// criptografar senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// gerar username automaticamente (simples)
$username = explode(" ", $nome)[0] . rand(100,999);

// inserir no banco
$sql = "INSERT INTO produtor 
(nome_produtor, CPF_produtor, RG_produtor, email_produtor, telefone_produtor, senha_produtor, username) 
VALUES 
('$nome','$cpf','$rg','$email','$telefone','$senha_hash','$username')";

if (mysqli_query($conn, $sql)) {
    echo "Cadastro realizado com sucesso! <a href='login_produtor.php'>Fazer login</a>";
} else {
    echo "Erro: " . mysqli_error($conn);
}
?>