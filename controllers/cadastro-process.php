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

// Verifica duplicidade
$sqlVerifica = "SELECT id_usuario, username, email_usuario FROM usuario WHERE username = ? OR email_usuario = ?";
$stmtVerifica = $conn->prepare($sqlVerifica);
$stmtVerifica->bind_param("ss", $username, $email);
$stmtVerifica->execute();
$resultVerifica = $stmtVerifica->get_result();

if ($resultVerifica->num_rows > 0) {
    $existente = $resultVerifica->fetch_assoc();
    if ($existente['username'] === $username) {
        $_SESSION['erro_cadastro'] = "Este username já está em uso.";
    } else {
        $_SESSION['erro_cadastro'] = "Este e-mail já está cadastrado.";
    }
    $stmtVerifica->close();
    header("Location: ../views/cadastro.php");
    exit;
}
$stmtVerifica->close();

// Insere Usuário
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);
$sql = "INSERT INTO usuario (username, nome_usuario, email_usuario, senha_usuario) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $username, $nome, $email, $senhaHash);

if ($stmt->execute()) {
    $_SESSION['sucesso_login'] = "Cadastro realizado com sucesso. Faça login.";
    header("Location: ../views/login.php");
    exit;
} else {
    $_SESSION['erro_cadastro'] = "Erro interno no servidor ao cadastrar.";
    header("Location: ../views/cadastro.php");
    exit;
}
?>