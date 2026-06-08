<?php
// A lógica de sessão e redirecionamento FOI MOVIDA para o dashboard-process.php.
// Aqui apenas preparamos as variáveis visuais com base no status de login.
$logado = isset($_SESSION['id_usuario']);

$username = $logado ? ($_SESSION['username'] ?? 'Usuário') : 'Visitante';
$email = $logado ? ($_SESSION['email_usuario'] ?? 'usuario@beatstreet.com') : '';

// Lógica para pegar as iniciais do usuário (apenas se logado)
$initials = "";
if ($logado) {
    $words = explode(" ", trim($username));
    if (count($words) >= 2) {
        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
    } else {
        $initials = strtoupper(substr($words[0], 0, 2));
    }
}
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
    
    <?php if ($logado): ?>
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


<nav class="nav-links nav-desktop">
    <a href="../controllers/evento-process.php" class="nav-item">
      <svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor"/></svg>
      Criar evento
    </a>
    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24" width="20" height="20"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z" fill="currentColor"/></svg>
      Meus eventos
    </a>
    <a href="../controllers/eventos-confirmados.php" class="nav-item">
      <svg viewBox="0 0 24 24" width="20" height="20"><path d="M22 10V6c0-1.11-.9-2-2-2H4c-1.1 0-1.99.89-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2 7.74c-1.75-.8-3-2.58-3-4.74s1.25-3.94 3-4.74V6H4v2.26c1.75.8 3 2.58 3 4.74s-1.25 3.94-3 4.74V18h16v-.26z" fill="currentColor"/></svg>
      Eventos Confirmados
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

    <?php else: ?>
        <a href="../views/login.php" class="nav-item" style="font-weight: 600;">Entrar</a>
        <a href="../views/cadastro.php" class="nav-item" style="background: #ff5e00; color: #fff; padding: 8px 20px; border-radius: 24px; font-weight: 600;">Criar conta</a>
    <?php endif; ?>

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
    <?php if ($logado): ?>
        <h1>Bem-vindo, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></h1>
    <?php else: ?>
        <h1>Conectando a cultura Hip Hop</h1>
    <?php endif; ?>
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
    // IMPORTANTE: Adicionado a variável $logado como terceiro parâmetro 
    // para alinhar com o Controlador que reescrevemos juntos.
    renderRowEventos("Hoje no BeatStreet", $eventosHoje ?? [], $logado);
    renderRowEventos("Próximos na sua Região", $eventosProximos ?? [], $logado);
    renderRowEventos("Batalhas de Rima", $eventosRima ?? [], $logado);
    renderRowEventos("Batalhas de Dança", $eventosDanca ?? [], $logado);
    renderRowEventos("Jams Oficiais", $eventosJam ?? [], $logado);
    renderRowEventos("Poetry Slams", $eventosSlam ?? [], $logado);
?>

<footer>
  <p>© 2026 BeatStreet - Todos os direitos reservados</p>
</footer>

<script src="../assets/js/menu.js"></script>
<script src="../assets/js/app.js"></script>
</body>
</html>