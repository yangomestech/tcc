<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - BeatStreet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styleLoginCadastro.css">
</head>
<body>

<div class="auth-container">
  <div class="auth-left">
    <div class="auth-left-content">
      <div class="logo">BEATSTREET</div>
      <h1>Entre para a comunidade</h1>
      <p>Crie sua conta e participe dos eventos</p>
    </div>
  </div>

  <div class="auth-right">
    <div class="auth-box">
      <h2>Criar conta</h2>

      <?php if (!empty($_SESSION['erro_cadastro'])): ?>
        <div class="mensagem-erro">
          <?= htmlspecialchars($_SESSION['erro_cadastro'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php unset($_SESSION['erro_cadastro']); ?>
      <?php endif; ?>

      <form action="../controllers/cadastro-process.php" method="POST">
        <div class="input-group">
          <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="input-group">
          <input type="text" name="nome_usuario" placeholder="Nome completo" required>
        </div>

        <div class="input-group">
          <input type="email" name="email_usuario" placeholder="E-mail" required>
        </div>

        <div class="input-group">
          <input type="password" name="senha_usuario" placeholder="Senha" required>
        </div>

        <div class="input-group">
          <input type="password" name="confirmar_senha" placeholder="Confirmar senha" required>
        </div>

        <button type="submit" class="btn-auth">Cadastrar</button>
      </form>

      <div class="links">
        <p>Já tem uma conta? <a href="login.php">Entrar</a></p>
      </div>
    </div>
  </div>
</div>

<script src="../assets/js/app.js"></script>
</body>
</html>
