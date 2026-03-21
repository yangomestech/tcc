<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
</head>
<body>

<h2>Cadastro de Usuário</h2>

<form action="processa_cadastro.php" method="POST">

    <input type="text" name="username" placeholder="Username" required><br><br>

    <input type="text" name="nome_usuario" placeholder="Nome completo" required><br><br>

    <input type="email" name="email_usuario" placeholder="Email" required><br><br>

    <input type="text" name="telefone_usuario" placeholder="Telefone" required><br><br>

    <input type="date" name="data_nascimento" required><br><br>

    <input type="password" name="senha_usuario" placeholder="Senha" required><br><br>

    <input type="password" name="confirmar_senha" placeholder="Confirmar senha" required><br><br>

    <button type="submit">Cadastrar</button>

</form>

<a href="login.php">Já tem conta? Login</a>

</body>
</html>