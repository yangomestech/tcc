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
  <link rel="stylesheet" href="../assets/css/styleDashboard.css">
</head>
<body>

<header class="header-sympla">
  <div class="logo">
    BEA<span class="roxo">T</span>S<span class="laranja">T</span>REET
  </div>
  
  <form class="search-sympla" action="busca.php" method="GET">
    
    <div class="search-box">
      <svg class="search-icon" viewBox="0 0 24 24"><path d="M10 2a8 8 0 016.32 12.9l4.387 4.387a1 1 0 01-1.414 1.415l-4.387-4.387A8 8 0 1110 2zm0 2a6 6 0 100 12 6 6 0 000-12z" fill="currentColor"/></svg>
      <input type="text" name="evento" placeholder="Buscar eventos, artistas...">
    </div>

    <div class="location-box">
  <svg class="location-icon" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z" fill="currentColor"/></svg>
  
  <span id="locationSelectedText">Qualquer lugar</span>
  
  <input type="hidden" name="cidade" id="cidadeInput" value="">
  
  <svg class="chevron-icon" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z" fill="currentColor"/></svg>

  <ul class="location-menu" id="locationMenu">
    <li class="location-item use-location" data-value="gps">
      <svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3c-.46-4.17-3.77-7.48-7.94-7.94V1h-2v2.06C6.83 3.52 3.52 6.83 3.06 11H1v2h2.06c.46 4.17 3.77 7.48 7.94 7.94V23h2v-2.06c4.17-.46 7.48-3.77 7.94-7.94H23v-2h-2.06zM12 19c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z" fill="currentColor"/></svg>
      Usar minha localização atual
    </li>
    <li class="location-item" data-value="">Qualquer lugar</li>
    <li class="location-item" data-value="SP">São Paulo (SP)</li>
    <li class="location-item" data-value="SC">Santa Catarina (SC)</li>
    <li class="location-item" data-value="RJ">Rio de Janeiro (RJ)</li>
    <li class="location-item" data-value="PR">Paraná (PR)</li>
    <li class="location-item" data-value="MG">Minas Gerais (MG)</li>
  </ul>
</div>
    
    <button type="submit" style="display: none;">Buscar</button>
  </form>

  <nav class="nav-links">
    <a href="../views/cadastro-evento.php">Criar evento</a>
    <a href="#">Meus eventos</a>
    <a href="../index.php?action=logout" class="logout-btn">Sair</a>
  </nav>
</header>

<section class="hero-top">
  <div class="hero-text">
    <h1>Bem-vindo <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p>Todos os eventos da cultura Hip Hop em um só lugar</p>
  </div>
</section>

<section class="sympla-carousel">
  <div class="carousel-track">
    <div class="carousel-item">
      <img src="../assets/img/computador1.jpg" alt="Imagem 1">
      <div class="carousel-caption">Batalha All Styles</div>
    </div>
    <div class="carousel-item">
      <img src="../assets/img/computador2.jpg" alt="Imagem 2">
      <div class="carousel-caption">Breaking Jam Oficial</div>
    </div>
    <div class="carousel-item">
      <img src="../assets/img/computador3.jpg" alt="Imagem 3">
      <div class="carousel-caption">House Session - SP</div>
    </div>
    <div class="carousel-item">
      <img src="../assets/img/computador4.jpg" alt="Imagem 4">
      <div class="carousel-caption">Popping Workshop</div>
    </div>
    <div class="carousel-item">
      <img src="https://images.unsplash.com/photo-1535525153412-5a42439a210d" alt="Imagem 5">
      <div class="carousel-caption">Final Nacional 2026</div>
    </div>
  </div>
  
  <div class="carousel-controls">
    <button id="prevBtn" class="nav-btn">❮</button>
    <button id="nextBtn" class="nav-btn">❯</button>
  </div>
  <div class="carousel-indicators"></div>
</section>

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
