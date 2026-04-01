<?php
session_start();
include("../config/conexao.php");

$username = trim($_POST['username'] ?? '');
$senha = trim($_POST['senha'] ?? '');

// buscar no banco
$sql = "SELECT * FROM produtor WHERE username = ? OR email_produtor = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}

$stmt->bind_param("ss", $username, $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $produtor = $result->fetch_assoc();

    // verificar senha
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
    echo "Usuário ou e-mail não encontrado";
}

$stmt->close();
$conn->close();
?>