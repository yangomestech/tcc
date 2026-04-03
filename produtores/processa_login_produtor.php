<?php
session_start();
include __DIR__ . '/../config/conexao.php';

$login = trim($_POST['login'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if (empty($login) || empty($senha)) {
    echo "Preencha todos os campos";
    exit();
}

$sql = "SELECT * FROM produtor WHERE username = ? OR email_produtor = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}

$stmt->bind_param("ss", $login, $login);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $produtor = $result->fetch_assoc();

    if (password_verify($senha, $produtor['senha_produtor'])) {
        $_SESSION['id_produtor'] = $produtor['id_produtor'];
        $_SESSION['username'] = $produtor['username'];
        $_SESSION['email_produtor'] = $produtor['email_produtor'];

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Senha incorreta";
    }
} else {
    echo "Produtor ou e-mail não encontrado";
}

$stmt->close();
$conn->close();
?>