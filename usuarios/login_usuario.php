<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - BeatStreet</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<!-- ------------------------------------------------------------------------------------------------------------------------------- -->

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      height: 100vh;
      background: #000;
      color: #fff;
      display: flex;
    }

    .container {
      display: flex;
      width: 100%;
    }

    .left {
      flex: 1;
      background: url('https://images.unsplash.com/photo-1514525253161-7a46d19cd819') no-repeat center/cover;
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 60px;
    }

    .left::before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(128,0,128,0.7), rgba(255,94,0,0.7));
    }

    .left-content {
      position: relative;
      z-index: 1;
    }

    .logo {
      font-size: 32px;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .left h1 {
      font-size: 28px;
      margin-bottom: 10px;
    }

    .left p {
      font-size: 14px;
      opacity: 0.8;
    }

    .right {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      background: rgba(20, 20, 20, 0.9);
      padding: 40px;
      border-radius: 15px;
      width: 320px;
      backdrop-filter: blur(10px);
      box-shadow: 0 0 30px rgba(255, 94, 0, 0.2);
    }

    .login-box h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    .mensagem-erro {
      background-color: rgba(255, 80, 80, 0.12);
      color: #ff8a8a;
      border: 1px solid rgba(255, 80, 80, 0.35);
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 16px;
      font-size: 14px;
      text-align: center;
    }

    .input-group {
      margin-bottom: 20px;
    }

    .input-group input {
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      border: none;
      background: #111;
      color: #fff;
      outline: none;
      transition: 0.3s;
    }

    .input-group input:focus {
      box-shadow: 0 0 10px rgba(255, 94, 0, 0.7);
    }

    .btn {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 30px;
      background: linear-gradient(90deg, purple, orange);
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn:hover {
      transform: scale(1.05);
      box-shadow: 0 0 15px rgba(255, 94, 0, 0.6);
    }

    .links {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
    }

    .links a {
      color: #ff7b00;
      text-decoration: none;
    }

    .links a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .left {
        display: none;
      }

      .right {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<!-- --------------------------------------------------------------------------------------------------- -->
 
<div class="container">

  <div class="left">
    <div class="left-content">
      <div class="logo">BEATSTREET</div>
      <h1>Conectando a cultura Hip Hop</h1>
      <p>Encontre eventos, batalhas e experiências</p>
    </div>
  </div>

  <div class="right">
    <div class="login-box">
      <h2>Entrar</h2>

      <?php if (!empty($_SESSION['erro_login_usuario'])): ?>
        <div class="mensagem-erro">
          <?php echo htmlspecialchars($_SESSION['erro_login_usuario']); ?>
        </div>
        <?php unset($_SESSION['erro_login_usuario']); ?>
      <?php endif; ?>

      <form action="processa_login_usuario.php" method="POST">
        <div class="input-group">
          <input
            type="text"
            name="login"
            placeholder="Username ou E-mail"
            required
            value="<?php echo htmlspecialchars($_SESSION['login_antigo_usuario'] ?? ''); ?>"
          >
        </div>

        <div class="input-group">
          <input type="password" name="senha" placeholder="Senha" required>
        </div>

        <button type="submit" class="btn">Entrar</button>
      </form>

      <div class="links">
        <p><a href="#">Esqueceu a senha?</a></p>
        <p>Não tem uma conta? <a href="cadastro_usuario.php">Criar conta</a></p>
      </div>
    </div>
  </div>

</div>

</body>
</html>
<?php unset($_SESSION['login_antigo_usuario']); ?>