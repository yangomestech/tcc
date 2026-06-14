<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Meus Favoritos - BeatStreet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styleDashboard.css">
  <link rel="stylesheet" href="../assets/css/styleFavoritos.css">
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
    <a href="../controllers/eventos-confirmados.php" class="nav-item">
      <svg viewBox="0 0 24 24" width="20" height="20"><path d="M22 10V6c0-1.11-.9-2-2-2H4c-1.1 0-1.99.89-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2 7.74c-1.75-.8-3-2.58-3-4.74s1.25-3.94 3-4.74V6H4v2.26c1.75.8 3 2.58 3 4.74s-1.25 3.94-3 4.74V18h16v-.26z" fill="currentColor"/></svg>
      Eventos confirmados
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
              <li><a href="../views/usuario.php"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/></svg> Minha conta</a></li>
              <li><a href="../controllers/favoritos-process.php"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/></svg> Favoritos</a></li>
              <li><a href="../controllers/evento-process.php"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor"/></svg> Criar evento</a></li>
              <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z" fill="currentColor"/></svg> Meus eventos</a></li>
              <li class="divider"></li>
              <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z" fill="currentColor"/></svg> Suporte</a></li>
              <li class="divider"></li>
              <li><a href="../controllers/logout-process.php" class="logout-link"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" fill="currentColor"/></svg> Sair</a></li>
            </ul>
      </div>
    </div>
  </nav>
</header>

<main class="favorites-container">
  <?php if (empty($eventosFavoritos)): ?>
    <div class="empty-state">
      <div class="empty-icon">
        <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/></svg>
      </div>
      <h2>Você ainda não possui eventos favoritos</h2>
      <p>Explore eventos da cena Hip Hop e adicione seus favoritos.</p>
      <a href="../controllers/dashboard-process.php" class="btn-explore">Explorar Eventos</a>
    </div>
  <?php else: ?>
    <section class="view-panel" id="viewPanel">
      <div class="animated-wrapper" id="animatedWrapper">
        <div class="panel-cover">
          <img id="panelImage" src="" alt="Capa do Evento">
          <div class="badge-type" id="panelType"></div>
        </div>
        <div class="panel-details">
          <h1 id="panelTitle"></h1>
          
          <div class="metadata-grid">
            <div class="meta-item">
              <svg viewBox="0 0 24 24" width="18" height="18"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V9h14v10zm0-12H5V5h14v2z" fill="currentColor"/></svg>
              <span id="panelDate"></span> às <span id="panelTime"></span>
            </div>
            <div class="meta-item">
              <svg viewBox="0 0 24 24" width="18" height="18"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z" fill="currentColor"/></svg>
              <span id="panelLocation"></span>
            </div>
            <div class="meta-item presence-badge">
              <svg viewBox="0 0 24 24" width="18" height="18"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" fill="currentColor"/></svg>
              <span id="panelPresences"></span> confirmados
            </div>
          </div>

          <div class="description-block">
            <h3>Sobre o evento</h3>
            <p id="panelDescription"></p>
          </div>

          <div class="action-row">
            <a href="../controllers/detalhe-evento.php?id=<?= $ev['id_evento'] ?>" id="panelLink" class="btn-main-action">Ver Evento Completo</a>
            <button id="btnRemoveFavorite" class="btn-danger-action">
              <svg viewBox="0 0 24 24" width="18" height="18"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" fill="currentColor"/></svg>
              Remover dos Favoritos
            </button>
            </a>
          </div>
        </div>
      </div>
    </section>

    <section class="list-panel">
      <div class="list-header">
        <h2>Sua Playlist de Eventos</h2>
        <span class="count-indicator"><?= count($eventosFavoritos) ?> eventos salvos</span>
      </div>
      <div class="playlist-scroll" id="playlistScroll">
        <?php foreach ($eventosFavoritos as $index => $ev): ?>
          <div class="playlist-item" data-id="<?= $ev['id_evento'] ?>" data-index="<?= $index ?>">
            <div class="item-index"><?= $index + 1 ?></div>
            <div class="item-thumbnail">
              <img src="<?= htmlspecialchars($ev['imagem_processada']) ?>" alt="Miniatura">
            </div>
            <div class="item-info">
              <h4><?= htmlspecialchars($ev['nome_evento'], ENT_QUOTES, 'UTF-8') ?></h4>
              <p class="item-date"><?= $ev['data_formatada'] ?></p>
              <p class="item-loc"><?= htmlspecialchars($ev['cidade'] . ' (' . strtoupper($ev['estado']) . ')', ENT_QUOTES, 'UTF-8') ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</main>

<div id="modalRemoveFavorite" class="modal-overlay" style="display: none;">
    <div class="modal-box">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
        </div>
        <h3>Remover dos Favoritos?</h3>
        <p>Este evento será removido da sua lista de favoritos. Você poderá adicioná-lo novamente a qualquer momento.</p>
        <div class="modal-actions">
            <button id="btnCancelRemove" class="btn-modal-cancel">Cancelar</button>
            <button id="btnConfirmRemove" class="btn-modal-delete">Remover dos Favoritos</button>
        </div>
    </div>
</div>

<script>
  const FAVORITOS_DATA = <?= json_encode($eventosFavoritos ?? []) ?>;
</script>
<script src="../assets/js/menu.js"></script>
<script src="../assets/js/favoritos.js"></script>
</body>
</html>

