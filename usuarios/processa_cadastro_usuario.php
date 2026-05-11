<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastro_usuario.php');
    exit;
}

include __DIR__ . '/../config/conexao.php';

$username  = trim($_POST['username'] ?? '');
$nome      = trim($_POST['nome_usuario'] ?? '');
$email     = trim($_POST['email_usuario'] ?? '');
$senha     = $_POST['senha_usuario'] ?? '';
$confirmar = $_POST['confirmar_senha'] ?? '';

if (empty($username) || empty($nome) || empty($email) || empty($senha) || empty($confirmar)) {
    $_SESSION['erro_cadastro_usuario'] = "Preencha todos os campos.";
    header("Location: cadastro_usuario.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro_cadastro_usuario'] = "E-mail inválido.";
    header("Location: cadastro_usuario.php");
    exit;
}

if ($senha !== $confirmar) {
    $_SESSION['erro_cadastro_usuario'] = "As senhas não coincidem.";
    header("Location: cadastro_usuario.php");
    exit;
}

$sqlVerifica = "SELECT id_usuario, username, email_usuario
                FROM usuario
                WHERE username = ? OR email_usuario = ?";

$stmtVerifica = $conn->prepare($sqlVerifica);

if (!$stmtVerifica) {
    die("Erro no prepare da verificação: " . $conn->error);
}

$stmtVerifica->bind_param("ss", $username, $email);
$stmtVerifica->execute();
$resultVerifica = $stmtVerifica->get_result();

if ($resultVerifica->num_rows > 0) {
    $existente = $resultVerifica->fetch_assoc();

    if ($existente['username'] === $username) {
        $_SESSION['erro_cadastro_usuario'] = "Este username já está em uso.";
    } elseif ($existente['email_usuario'] === $email) {
        $_SESSION['erro_cadastro_usuario'] = "Este e-mail já está cadastrado.";
    } else {
        $_SESSION['erro_cadastro_usuario'] = "Já existe um usuário com esses dados.";
    }

    $stmtVerifica->close();
    header("Location: cadastro_usuario.php");
    exit;
}

$stmtVerifica->close();

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuario (username, nome_usuario, email_usuario, senha_usuario)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}

$stmt->bind_param("ssss", $username, $nome, $email, $senhaHash);

if ($stmt->execute()) {
    $_SESSION['sucesso_cadastro_usuario'] = "Cadastro realizado com sucesso. Faça login.";
    header("Location: login_usuario.php");
    exit;
}

$_SESSION['erro_cadastro_usuario'] = "Erro ao cadastrar: " . $stmt->error;
header("Location: cadastro_usuario.php");
exit;
?>