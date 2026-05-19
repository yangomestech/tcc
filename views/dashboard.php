<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'] ?? 'Usuário';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - BeatStreet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
  <div class="logo">
    BEA<span class="roxo">T</span>S<span class="laranja">T</span>REET
  </div>
  <nav>
    <a href="#">Início</a>
    <a href="#">Eventos</a>
    <a href="#">Adicionar</a>
    <a href="#">Sobre</a>
    <a href="#">Contato</a>
    <a href="../index.php?action=logout" style="color: #ff8a8a;">Sair</a>
  </nav>
</header>

<section class="hero">
  <div class="hero-text">
    <h1>Bem-vindo, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p>Todos os eventos da cultura Hip Hop em um só lugar</p>
    <button class="btn">Ver eventos</button>
    <!-- Futuramente, a lógica de "Adicionar evento" checará o upgrade de conta do usuário aqui -->
    <button class="btn">Adicionar evento</button> 
  </div>
  
</section>

<div class="search">
  <input type="text" placeholder="Buscar evento...">
  <input type="text" placeholder="Cidade">
  <input type="text" placeholder="Estilo">
</div>

<section class="section">
  <h2>Eventos próximos</h2>
  <div class="cards">
    <div class="card">
      <img src="https://images.unsplash.com/photo-1504609813442-a8924e83f76e" alt="Batalha">
      <div class="card-content">
        <h3>Batalha All Styles</h3>
        <p>São Paulo - 12 Maio</p>
        <button>Ver detalhes</button>
      </div>
    </div>
    <div class="card">
      <img src="https://images.unsplash.com/photo-1521334884684-d80222895322" alt="Breaking Jam">
      <div class="card-content">
        <h3>Breaking Jam</h3>
        <p>Rio de Janeiro - 18 Maio</p>
        <button>Ver detalhes</button>
      </div>
    </div>
    <div class="card">
      <img src="https://images.unsplash.com/photo-1520975922323-3c36e27c0f06" alt="House Session">
      <div class="card-content">
        <h3>House Session</h3>
        <p>Belo Horizonte - 25 Maio</p>
        <button>Ver detalhes</button>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <h2>Eventos por estilo</h2>
  <div class="styles">
    <div class="style-card">Hip Hop</div>
    <div class="style-card">Breaking</div>
    <div class="style-card">House</div>
    <div class="style-card">Popping</div>
    <div class="style-card">Locking</div>
    <div class="style-card">All Styles</div>
  </div>
</section>

<footer>
  <p>© 2026 BeatStreet - Todos os direitos reservados</p>
</footer>

<script src="../assets/js/app.js"></script>
</body>
</html>