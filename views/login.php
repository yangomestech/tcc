<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - BeatStreet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="auth-container">
  <div class="auth-left">
    <div class="auth-left-content">
      <div class="logo">BEATSTREET</div>
      <h1>Conectando a cultura Hip Hop</h1>
      <p>Encontre eventos, batalhas e experiências</p>
    </div>
  </div>

  <div class="auth-right">
    <div class="auth-box">
      <h2>Entrar</h2>

      <?php if (!empty($_SESSION['erro_login'])): ?>
        <div class="mensagem-erro">
          <?= htmlspecialchars($_SESSION['erro_login'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php unset($_SESSION['erro_login']); ?>
      <?php endif; ?>

      <?php if (!empty($_SESSION['sucesso_login'])): ?>
        <div class="mensagem-sucesso">
          <?= htmlspecialchars($_SESSION['sucesso_login'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php unset($_SESSION['sucesso_login']); ?>
      <?php endif; ?>

      <form action="../controllers/login-process.php" method="POST">
        <div class="input-group">
          <input 
            type="text" 
            name="login" 
            placeholder="Username ou E-mail" 
            required
            value="<?= htmlspecialchars($_SESSION['login_antigo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
          >
        </div>

        <div class="input-group">
          <input type="password" name="senha" placeholder="Senha" required>
        </div>

        <button type="submit" class="btn-auth">Entrar</button>
      </form>

      <div class="links">
        <p><a href="#">Esqueceu a senha?</a></p>
        <p>Não tem uma conta? <a href="cadastro.php">Criar conta</a></p>
      </div>
    </div>
  </div>
</div>

<script src="../assets/js/app.js"></script>
</body>
</html>
<?php unset($_SESSION['login_antigo']); ?>