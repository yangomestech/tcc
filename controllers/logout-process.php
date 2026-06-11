<?php
// Inicia a sessão para podermos acessá-la e destruí-la
session_start();

// 1. Esvazia todas as variáveis da sessão atual
$_SESSION = array();

// 2. Invalida o cookie da sessão no navegador do usuário (boa prática de segurança)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destrói a sessão no servidor
session_destroy();

// 4. Redireciona o usuário de volta para a tela de login
header("Location: ../views/login.php");
exit();
?>