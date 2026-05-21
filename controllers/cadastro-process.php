<?php
session_start();
require_once __DIR__ . '/../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/cadastro.php');
    exit;
}

$username  = trim($_POST['username'] ?? '');
$nome      = trim($_POST['nome_usuario'] ?? '');
$email     = trim($_POST['email_usuario'] ?? '');
$senha     = $_POST['senha_usuario'] ?? '';
$confirmar = $_POST['confirmar_senha'] ?? '';

if (empty($username) || empty($nome) || empty($email) || empty($senha) || empty($confirmar)) {
    $_SESSION['erro_cadastro'] = "Preencha todos os campos.";
    header("Location: ../views/cadastro.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro_cadastro'] = "E-mail inválido.";
    header("Location: ../views/cadastro.php");
    exit;
}

if ($senha !== $confirmar) {
    $_SESSION['erro_cadastro'] = "As senhas não coincidem.";
    header("Location: ../views/cadastro.php");
    exit;
}

// Verifica duplicidade usando PDO
$sqlVerifica = "SELECT id_usuario, username, email_usuario FROM usuario WHERE username = :username OR email_usuario = :email";
$stmtVerifica = $conn->prepare($sqlVerifica);
$stmtVerifica->execute([
    ':username' => $username,
    ':email' => $email
]);

$existente = $stmtVerifica->fetch(PDO::FETCH_ASSOC);

if ($existente) {
    if ($existente['username'] === $username) {
        $_SESSION['erro_cadastro'] = "Este username já está em uso.";
    } else {
        $_SESSION['erro_cadastro'] = "Este e-mail já está cadastrado.";
    }
    header("Location: ../views/cadastro.php");
    exit;
}

// Insere Usuário usando PDO
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);
$sql = "INSERT INTO usuario (username, nome_usuario, email_usuario, senha_usuario) VALUES (:username, :nome, :email, :senha)";
$stmt = $conn->prepare($sql);

$sucesso = $stmt->execute([
    ':username' => $username,
    ':nome' => $nome,
    ':email' => $email,
    ':senha' => $senhaHash
]);

if ($sucesso) {
    $_SESSION['sucesso_login'] = "Cadastro realizado com sucesso. Faça login.";
    header("Location: ../views/login.php");
    exit;
} else {
    $_SESSION['erro_cadastro'] = "Erro interno no servidor ao cadastrar.";
    header("Location: ../views/cadastro.php");
    exit;
}
?>