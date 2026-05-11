<?php
session_start();
include __DIR__ . '/../config/conexao.php';
include __DIR__ . '/../config/registrar_log.php';

$login = trim($_POST['login'] ?? '');
$senha = $_POST['senha'] ?? '';

$_SESSION['login_antigo_usuario'] = $login;

if (empty($login) || empty($senha)) {
    registrarLog($conn, null, null, 'LOGIN_FALHA', 'Tentativa de login com campos vazios');
    $_SESSION['erro_login_usuario'] = "Preencha todos os campos";
    header("Location: login_usuario.php");
    exit();
}

$sql = "SELECT id_usuario, username, email_usuario, senha_usuario, tentativas_login, bloqueado_ate
        FROM usuario
        WHERE username = ? OR email_usuario = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}

$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    if (!empty($usuario['bloqueado_ate']) && strtotime($usuario['bloqueado_ate']) > time()) {
        registrarLog($conn, $usuario['id_usuario'], null, 'LOGIN_BLOQUEADO', 'Tentativa de login em conta temporariamente bloqueada');
        $_SESSION['erro_login_usuario'] = "Muitas tentativas de login. Tente novamente mais tarde.";
        header("Location: login_usuario.php");
        exit();
    }

    if (password_verify($senha, $usuario['senha_usuario'])) {
        $sqlReset = "UPDATE usuario SET tentativas_login = 0, bloqueado_ate = NULL WHERE id_usuario = ?";
        $stmtReset = $conn->prepare($sqlReset);
        $stmtReset->bind_param("i", $usuario['id_usuario']);
        $stmtReset->execute();
        $stmtReset->close();

        unset($_SESSION['login_antigo_usuario']);

        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['username'] = $usuario['username'];
        $_SESSION['email_usuario'] = $usuario['email_usuario'];

        registrarLog($conn, $usuario['id_usuario'], null, 'LOGIN_SUCESSO', 'Usuário autenticado com sucesso');

        header("Location: dashboard.php");
        exit();
    }

    $novaTentativa = $usuario['tentativas_login'] + 1;

    if ($novaTentativa >= 5) {
        $sqlBloqueio = "UPDATE usuario
                        SET tentativas_login = ?, bloqueado_ate = DATE_ADD(NOW(), INTERVAL 10 MINUTE)
                        WHERE id_usuario = ?";
        $stmtBloqueio = $conn->prepare($sqlBloqueio);
        $stmtBloqueio->bind_param("ii", $novaTentativa, $usuario['id_usuario']);
        $stmtBloqueio->execute();
        $stmtBloqueio->close();

        registrarLog($conn, $usuario['id_usuario'], null, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas');

        $_SESSION['erro_login_usuario'] = "Muitas tentativas de login. Tente novamente em 10 minutos.";
        header("Location: login_usuario.php");
        exit();
    } else {
        $sqlTentativa = "UPDATE usuario SET tentativas_login = ? WHERE id_usuario = ?";
        $stmtTentativa = $conn->prepare($sqlTentativa);
        $stmtTentativa->bind_param("ii", $novaTentativa, $usuario['id_usuario']);
        $stmtTentativa->execute();
        $stmtTentativa->close();

        registrarLog($conn, $usuario['id_usuario'], null, 'LOGIN_FALHA', 'Falha no login: senha incorreta');

        $_SESSION['erro_login_usuario'] = "Senha incorreta";
        header("Location: login_usuario.php");
        exit();
    }
} else {
    registrarLog($conn, null, null, 'LOGIN_FALHA', 'Falha no login: usuário ou e-mail não encontrado');
    $_SESSION['erro_login_usuario'] = "Usuário ou e-mail não encontrado";
    header("Location: login_usuario.php");
    exit();
}

$stmt->close();
$conn->close();
?>