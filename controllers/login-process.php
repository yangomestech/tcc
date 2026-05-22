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

// Consulta usando PDO
$sql = "SELECT id_usuario, username, email_usuario, senha_usuario, tentativas_login, bloqueado_ate 
        FROM usuario 
        WHERE username = :login OR email_usuario = :login_email";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':login' => $login,
    ':login_email' => $login
]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    if (!empty($usuario['bloqueado_ate']) && strtotime($usuario['bloqueado_ate']) > time()) {
        $_SESSION['erro_login'] = "Muitas tentativas de login. Tente novamente mais tarde.";
        header("Location: ../views/login.php");
        exit();
    }

    if (password_verify($senha, $usuario['senha_usuario'])) {
        // Reset de tentativas usando PDO
        $sqlReset = "UPDATE usuario SET tentativas_login = 0, bloqueado_ate = NULL WHERE id_usuario = :id";
        $stmtReset = $conn->prepare($sqlReset);
        $stmtReset->execute([':id' => $usuario['id_usuario']]);

        session_regenerate_id(true); 

        unset($_SESSION['login_antigo']);
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['username']   = $usuario['username'];

        header("Location: ../index.php"); 
        exit();
    } else {
        $novaTentativa = (int)$usuario['tentativas_login'] + 1;
        if ($novaTentativa >= 5) {
            // Bloqueio usando PDO
            $sqlBloqueio = "UPDATE usuario SET tentativas_login = :tentativas, bloqueado_ate = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE id_usuario = :id";
            $stmtBloqueio = $conn->prepare($sqlBloqueio);
            $stmtBloqueio->execute([
                ':tentativas' => $novaTentativa,
                ':id' => $usuario['id_usuario']
            ]);
            $_SESSION['erro_login'] = "Muitas tentativas de login. Tente novamente em 10 minutos.";
        } else {
            // Atualiza tentativa usando PDO
            $sqlTentativa = "UPDATE usuario SET tentativas_login = :tentativas WHERE id_usuario = :id";
            $stmtTentativa = $conn->prepare($sqlTentativa);
            $stmtTentativa->execute([
                ':tentativas' => $novaTentativa,
                ':id' => $usuario['id_usuario']
            ]);
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
?>
