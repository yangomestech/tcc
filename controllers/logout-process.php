<?php
// Inicia a sessão para podermos destruí-la
session_start();

// Esvazia todas as variáveis da sessão atual
$_SESSION = array();

// Se o sistema usar cookies de sessão, destruímos o cookie também por segurança
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destrói a sessão no servidor
session_destroy();

// Redireciona o usuário para a página de login (ou página inicial)
header("Location: ../views/login.php");
exit();
?>