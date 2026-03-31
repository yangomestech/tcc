<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastro_usuario.php');
    exit;
}

include __DIR__ . '/../config/conexao.php';

echo "<pre>";
print_r($_POST);
echo "</pre>";

$username = trim($_POST['username']);
$nome = trim($_POST['nome_usuario']);
$email = trim($_POST['email_usuario']);
$senha = $_POST['senha_usuario'];
$confirmar = $_POST['confirmar_senha'];

if ($senha !== $confirmar) {
    die("Erro: As senhas não coincidem.");
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuario (username, nome_usuario, email_usuario, senha_usuario)
        VALUES ('$username', '$nome', '$email', '$senhaHash')";

echo "SQL gerado:<br>";
echo $sql . "<br><br>";

$resultado = $conn->query($sql);
var_dump($resultado);
echo "<br>";
echo "Erro do MySQL: " . $conn->error;

if ($resultado === TRUE) {
    header("Location: dashboard.php");
} else {
    echo "Erro MySQL: " . $conn->error;
}
?>