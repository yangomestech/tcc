<?php
session_start();
include __DIR__ . '/../config/conexao.php';

$login = trim($_POST['login'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if (empty($login) || empty($senha)) {
    echo "Preencha todos os campos";
    exit();
}

$sql = "SELECT * FROM usuario WHERE username = ? OR email_usuario = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}

$stmt->bind_param("ss", $login, $login);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    if (password_verify($senha, $usuario['senha_usuario'])) {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['username'] = $usuario['username'];
        $_SESSION['email_usuario'] = $usuario['email_usuario'];

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Senha incorreta";
    }
} else {
    echo "Usuário ou e-mail não encontrado";
}

$stmt->close();
$conn->close();
?>