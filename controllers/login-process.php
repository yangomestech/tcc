<?php
session_start();
require_once __DIR__ . '/../config/conexao.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/login.php");
    exit();
}

$login = trim($_POST['login'] ?? '');
$senha = $_POST['senha'] ?? '';
$_SESSION['login_antigo'] = $login;

if (empty($login) || empty($senha)) {
    $_SESSION['erro_login'] = "Preencha todos os campos.";
    header("Location: ../views/login.php");
    exit();
}

$sql = "SELECT id_usuario, username, email_usuario, senha_usuario, tentativas_login, bloqueado_ate 
        FROM usuario 
        WHERE username = ? OR email_usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    if (!empty($usuario['bloqueado_ate']) && strtotime($usuario['bloqueado_ate']) > time()) {
        $_SESSION['erro_login'] = "Muitas tentativas de login. Tente novamente mais tarde.";
        header("Location: ../views/login.php");
        exit();
    }

    if (password_verify($senha, $usuario['senha_usuario'])) {
        $sqlReset = "UPDATE usuario SET tentativas_login = 0, bloqueado_ate = NULL WHERE id_usuario = ?";
        $stmtReset = $conn->prepare($sqlReset);
        $stmtReset->bind_param("i", $usuario['id_usuario']);
        $stmtReset->execute();
        $stmtReset->close();

        session_regenerate_id(true); 

        unset($_SESSION['login_antigo']);
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['username']   = $usuario['username'];

        header("Location: ../index.php"); 
        exit();
    } else {
        $novaTentativa = (int)$usuario['tentativas_login'] + 1;
        if ($novaTentativa >= 5) {
            $sqlBloqueio = "UPDATE usuario SET tentativas_login = ?, bloqueado_ate = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE id_usuario = ?";
            $stmtBloqueio = $conn->prepare($sqlBloqueio);
            $stmtBloqueio->bind_param("ii", $novaTentativa, $usuario['id_usuario']);
            $stmtBloqueio->execute();
            $stmtBloqueio->close();
            $_SESSION['erro_login'] = "Muitas tentativas de login. Tente novamente em 10 minutos.";
        } else {
            $sqlTentativa = "UPDATE usuario SET tentativas_login = ? WHERE id_usuario = ?";
            $stmtTentativa = $conn->prepare($sqlTentativa);
            $stmtTentativa->bind_param("ii", $novaTentativa, $usuario['id_usuario']);
            $stmtTentativa->execute();
            $stmtTentativa->close();
            $_SESSION['erro_login'] = "Credenciais incorretas."; 
        }
        header("Location: ../views/login.php");
        exit();
    }
} else {
    $_SESSION['erro_login'] = "Credenciais incorretas."; 
    header("Location: ../views/login.php");
    exit();
}

$stmt->close();
$conn->close();
?>