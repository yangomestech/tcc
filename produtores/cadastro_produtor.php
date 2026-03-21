<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produtor</title>
</head>
<body>

<h2>Cadastro de Produtor</h2>

<form action="processa_cadastro.php" method="POST">

    <input type="text" name="nome_produtor" placeholder="Nome completo" required><br><br>

    <input type="text" name="CPF_produtor" placeholder="CPF (somente números)" pattern="\d{11}" required><br><br>

    <input type="text" name="RG_produtor" placeholder="RG" required><br><br>

    <input type="email" name="email_produtor" placeholder="Email" required><br><br>

    <input type="tel" name="telefone_produtor" placeholder="Telefone" required><br><br>

    <input type="password" name="senha_produtor" placeholder="Senha (mínimo 6 caracteres)" minlength="6" required><br><br>

    <input type="password" name="confirmar_senha" placeholder="Confirmar senha" required><br><br>

    <button type="submit">Cadastrar</button>

</form>

<a href="login.php">Já tem conta? Login</a>

</body>
</html>