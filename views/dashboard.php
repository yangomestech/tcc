<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: '/../views/login.php");
    exit();
}
$username = $_SESSION['username'] ?? 'Usuário';

// Lógica para pegar as iniciais do usuário
$words = explode(" ", trim($username));
$initials = "";
if (count($words) >= 2) {
    $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
} else {
    $initials = strtoupper(substr($words[0], 0, 2));
}

// Fallback caso o e-mail não esteja na sessão
$email = $_SESSION['email_usuario'] ?? 'usuario@beatstreet.com';
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
        <a href="../controllers/dashboard-process.php" class="logo">
            BEA<span class="roxo">T</span>S<span class="laranja">T</span>REET
        </a>
  


<nav class="nav-links nav-desktop">
    <a href="../controllers/evento-process.php" class="nav-item">
      <svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor"/></svg>
      Criar evento
    </a>
    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24" width="20" height="20"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z" fill="currentColor"/></svg>
      Meus eventos
    </a>
    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24" width="20" height="20"><path d="M22 10V6c0-1.11-.9-2-2-2H4c-1.1 0-1.99.89-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2 7.74c-1.75-.8-3-2.58-3-4.74s1.25-3.94 3-4.74V6H4v2.26c1.75.8 3 2.58 3 4.74s-1.25 3.94-3 4.74V18h16v-.26z" fill="currentColor"/></svg>
      Meus ingressos
    </a>

    <div class="user-menu-container">
      <button class="user-profile-btn" id="userMenuBtn">
        <svg class="hamburger-icon" viewBox="0 0 24 24" width="24" height="24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" fill="currentColor"/></svg>
        <div class="user-initials"><?= $initials; ?></div>
      </button>

      <div class="user-dropdown" id="userDropdown">
        <div class="dropdown-header">
          <div class="user-initials-large"><?= $initials; ?></div>
          <div class="user-info">
            <strong><?= htmlspecialchars(strtoupper($username), ENT_QUOTES, 'UTF-8'); ?></strong>
            <span><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></span>
          </div>
        </div>

        <ul class="dropdown-list">
          <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/></svg> Minha conta</a></li>
          <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/></svg> Favoritos</a></li>
          <li><a href="../controllers/evento-process.php"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor"/></svg> Criar evento</a></li>
          <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z" fill="currentColor"/></svg> Meus eventos</a></li>
          <li class="divider"></li>
          <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z" fill="currentColor"/></svg> Suporte</a></li>
          <li class="divider"></li>
          <li><a href="../index.php?action=logout" class="logout-link"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" fill="currentColor"/></svg> Sair</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

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

<section class="hero-top">
  <div class="hero-text">
    <h1>Bem-vindo <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p>Todos os eventos da cultura Hip Hop em um só lugar</p>
  </div>
</section>

<section class="sympla-carousel">
  <div class="carousel-track">
    <?php if(!empty($eventosCarrossel)): ?>
        <?php foreach($eventosCarrossel as $index => $ev): 
            $imgFallback = getImagemFallback($ev['imagem_evento'] ?? '', $ev['id_tipo']);
        ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
          <img src="<?= htmlspecialchars($imgFallback) ?>" alt="<?= htmlspecialchars($ev['nome_evento']) ?>">
          <div class="carousel-caption"><?= htmlspecialchars($ev['nome_evento']) ?></div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; color:#888;">Nenhum evento em destaque.</p>
    <?php endif; ?>
  </div>
  
  <div class="carousel-controls">
    <button id="prevBtn" class="nav-btn">❮</button>
    <button id="nextBtn" class="nav-btn">❯</button>
  </div>
  <div class="carousel-indicators"></div>
</section>

<?php
    renderRowEventos("Hoje no BeatStreet", $eventosHoje);
    renderRowEventos("Próximos na sua Região", $eventosProximos);
    renderRowEventos("Batalhas de Rima", $eventosRima);
    renderRowEventos("Batalhas de Dança", $eventosDanca);
    renderRowEventos("Jams Oficiais", $eventosJam);
    renderRowEventos("Poetry Slams", $eventosSlam);
?>


<footer>
  <p>© 2026 BeatStreet - Todos os direitos reservados</p>
</footer>

<script src="../assets/js/menu.js"></script>
<script src="../assets/js/app.js"></script>
</body>
</html>

