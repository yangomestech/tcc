<?php
session_start();
include __DIR__ . '/../config/conexao.php';
include __DIR__ . '/../config/registrar_log.php';

$login = trim($_POST['login'] ?? '');
$senha = $_POST['senha'] ?? '';

$_SESSION['login_antigo_produtor'] = $login;

if (empty($login) || empty($senha)) {
    registrarLog($conn, null, null, 'LOGIN_FALHA', 'Tentativa de login do produtor com campos vazios');
    $_SESSION['erro_login_produtor'] = "Preencha todos os campos.";
    header("Location: login_produtor.php");
    exit();
}

$sql = "SELECT id_produtor, username, email_produtor, senha_produtor, tentativas_login, bloqueado_ate
        FROM produtor
        WHERE username = ? OR email_produtor = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}

$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $produtor = $result->fetch_assoc();

    if (!empty($produtor['bloqueado_ate']) && strtotime($produtor['bloqueado_ate']) > time()) {
        registrarLog($conn, null, $produtor['id_produtor'], 'LOGIN_BLOQUEADO', 'Tentativa de login em conta de produtor temporariamente bloqueada');
        $_SESSION['erro_login_produtor'] = "Muitas tentativas de login. Tente novamente mais tarde.";
        header("Location: login_produtor.php");
        exit();
    }

    if (password_verify($senha, $produtor['senha_produtor'])) {
        $sqlReset = "UPDATE produtor SET tentativas_login = 0, bloqueado_ate = NULL WHERE id_produtor = ?";
        $stmtReset = $conn->prepare($sqlReset);

        if ($stmtReset) {
            $stmtReset->bind_param("i", $produtor['id_produtor']);
            $stmtReset->execute();
            $stmtReset->close();
        }

        unset($_SESSION['login_antigo_produtor']);

        $_SESSION['id_produtor'] = $produtor['id_produtor'];
        $_SESSION['username_produtor'] = $produtor['username'];
        $_SESSION['email_produtor'] = $produtor['email_produtor'];

        registrarLog($conn, null, $produtor['id_produtor'], 'LOGIN_SUCESSO', 'Produtor autenticado com sucesso');

        header("Location: dashboard.php");
        exit();
    }

    $novaTentativa = (int)$produtor['tentativas_login'] + 1;

    if ($novaTentativa >= 5) {
        $sqlBloqueio = "UPDATE produtor
                        SET tentativas_login = ?, bloqueado_ate = DATE_ADD(NOW(), INTERVAL 10 MINUTE)
                        WHERE id_produtor = ?";

        $stmtBloqueio = $conn->prepare($sqlBloqueio);

        if ($stmtBloqueio) {
            $stmtBloqueio->bind_param("ii", $novaTentativa, $produtor['id_produtor']);
            $stmtBloqueio->execute();
            $stmtBloqueio->close();
        }

        registrarLog($conn, null, $produtor['id_produtor'], 'LOGIN_BLOQUEADO', 'Produtor bloqueado temporariamente por excesso de tentativas');

        $_SESSION['erro_login_produtor'] = "Muitas tentativas de login. Tente novamente em 10 minutos.";
        header("Location: login_produtor.php");
        exit();
    }

    $sqlTentativa = "UPDATE produtor SET tentativas_login = ? WHERE id_produtor = ?";
    $stmtTentativa = $conn->prepare($sqlTentativa);

    if ($stmtTentativa) {
        $stmtTentativa->bind_param("ii", $novaTentativa, $produtor['id_produtor']);
        $stmtTentativa->execute();
        $stmtTentativa->close();
    }

    registrarLog($conn, null, $produtor['id_produtor'], 'LOGIN_FALHA', 'Falha no login do produtor: senha incorreta');

    $_SESSION['erro_login_produtor'] = "Senha incorreta.";
    header("Location: login_produtor.php");
    exit();
}

registrarLog($conn, null, null, 'LOGIN_FALHA', 'Falha no login do produtor: usuário ou e-mail não encontrado');
$_SESSION['erro_login_produtor'] = "Produtor ou e-mail não encontrado.";
header("Location: login_produtor.php");
exit();
?>