<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - BeatStreet</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

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

    /* LADO ESQUERDO */
    .left {
      flex: 1;
      background: url('https://images.unsplash.com/photo-1508609349937-5ec4ae374ebf') no-repeat center/cover;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
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
      text-align: center;
    }

    .logo {
      font-size: 32px;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .left h1 {
      font-size: 26px;
      margin-bottom: 10px;
    }

    .left p {
      font-size: 14px;
      opacity: 0.8;
    }

    /* LADO DIREITO */
    .right {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .signup-box {
      background: rgba(20, 20, 20, 0.9);
      padding: 40px;
      border-radius: 15px;
      width: 350px;
      backdrop-filter: blur(10px);
      box-shadow: 0 0 30px rgba(255, 94, 0, 0.2);
    }

    .signup-box h2 {
      text-align: center;
      margin-bottom: 25px;
    }

    .input-group {
      margin-bottom: 15px;
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

    .submit {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 30px;
      background: linear-gradient(90deg, purple, orange);
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
      margin-top: 10px;
    }

    .submit:hover {
      transform: scale(1.05);
      box-shadow: 0 0 15px rgba(255, 94, 0, 0.6);
    }

    .links {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .links a {
      color: #ff7b00;
      text-decoration: none;
    }

    .links a:hover {
      text-decoration: underline;
    }

    /* RESPONSIVO */
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

<div class="container">

  <!-- ESQUERDA -->
  <div class="left">
    <div class="left-content">
      <div class="logo">BEATSTREET</div>
      <h1>Entre para a comunidade</h1>
      <p>Crie sua conta e participe dos eventos</p>
    </div>
  </div>

    
  <!-- DIREITA -->
  <div class="right">
    <div class="signup-box">
      <h2>Criar conta</h2>
<form action="processa_cadastro_usuario.php" method="POST">
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

      <button type="submit" class="submit">Cadastrar</button>
</form>
      <div class="links">
        <p>Já tem uma conta? <a href="login_usuario.php">Entrar</a></p>
        <p><a href="/../tcc/tcc/produtores/cadastro_produtor.php">Sou produtor</a></p>
      </div>
    </div>
  </div>

</div>

</body>
</html>