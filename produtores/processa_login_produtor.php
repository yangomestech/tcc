<?php
session_start();
include("../config/conexao.php");

$username = $_POST['username'];
$senha = $_POST['senha'];

// buscar no banco
$sql = "SELECT * FROM produtor WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {

    $produtor = mysqli_fetch_assoc($result);

    // verificar senha
    if (password_verify($senha, $produtor['senha_produtor'])) {

        $_SESSION['id_produtor'] = $produtor['id_produtor'];
        $_SESSION['nome_produtor'] = $produtor['nome_produtor'];

        echo "Login realizado com sucesso!";
        // depois você pode redirecionar:
        // header("Location: dashboard.php");

    } else {
        echo "Senha incorreta";
    }

} else {
    echo "Usuário não encontrado";
}
?>