<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastro_produtor.php');
    exit;
}

include __DIR__ . '/../config/conexao.php';

$username   = trim($_POST['username'] ?? '');
$nome       = trim($_POST['nome_produtor'] ?? '');
$cpf        = trim($_POST['CPF_produtor'] ?? '');
$rg         = trim($_POST['RG_produtor'] ?? '');
$email      = trim($_POST['email_produtor'] ?? '');
$telefone   = trim($_POST['telefone_produtor'] ?? '');
$senha      = $_POST['senha_produtor'] ?? '';
$confirmar  = $_POST['confirmar_senha'] ?? '';

if (
    empty($username) || empty($nome) || empty($cpf) || empty($rg) ||
    empty($email) || empty($telefone) || empty($senha) || empty($confirmar)
) {
    $_SESSION['erro_cadastro_produtor'] = "Preencha todos os campos.";
    header("Location: cadastro_produtor.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro_cadastro_produtor'] = "E-mail inválido.";
    header("Location: cadastro_produtor.php");
    exit;
}

if ($senha !== $confirmar) {
    $_SESSION['erro_cadastro_produtor'] = "As senhas não coincidem.";
    header("Location: cadastro_produtor.php");
    exit;
}

$sqlVerifica = "SELECT id_produtor, username, email_produtor, CPF_produtor, RG_produtor
                FROM produtor
                WHERE username = ? OR email_produtor = ? OR CPF_produtor = ? OR RG_produtor = ?";

$stmtVerifica = $conn->prepare($sqlVerifica);

if (!$stmtVerifica) {
    die("Erro no prepare da verificação: " . $conn->error);
}

$stmtVerifica->bind_param("ssss", $username, $email, $cpf, $rg);
$stmtVerifica->execute();
$resultVerifica = $stmtVerifica->get_result();

if ($resultVerifica->num_rows > 0) {
    $registroExistente = $resultVerifica->fetch_assoc();

    if ($registroExistente['username'] === $username) {
        $_SESSION['erro_cadastro_produtor'] = "Este nome de usuário já está em uso.";
    } elseif ($registroExistente['email_produtor'] === $email) {
        $_SESSION['erro_cadastro_produtor'] = "Este e-mail já está cadastrado.";
    } elseif ($registroExistente['CPF_produtor'] === $cpf) {
        $_SESSION['erro_cadastro_produtor'] = "Este CPF já está cadastrado.";
    } elseif ($registroExistente['RG_produtor'] === $rg) {
        $_SESSION['erro_cadastro_produtor'] = "Este RG já está cadastrado.";
    } else {
        $_SESSION['erro_cadastro_produtor'] = "Já existe um produtor com esses dados.";
    }

    $stmtVerifica->close();
    $conn->close();

    header("Location: cadastro_produtor.php");
    exit;
}

$stmtVerifica->close();

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO produtor
        (nome_produtor, CPF_produtor, RG_produtor, email_produtor, telefone_produtor, senha_produtor, username)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro no prepare do insert: " . $conn->error);
}

$stmt->bind_param("sssssss", $nome, $cpf, $rg, $email, $telefone, $senha_hash, $username);

if ($stmt->execute()) {
    $_SESSION['sucesso_cadastro_produtor'] = "Cadastro realizado com sucesso. Faça login.";
    header("Location: login_produtor.php");
    exit;
}

$_SESSION['erro_cadastro_produtor'] = "Erro ao cadastrar produtor: " . $stmt->error;
header("Location: cadastro_produtor.php");
exit;
?>