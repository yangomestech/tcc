<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/.conexao.php';

// --- Lógica de Cabeçalho e Sessão ---
$logado = isset($_SESSION['id_usuario']);
$username = $logado ? ($_SESSION['username'] ?? 'Usuário') : 'Visitante';
$email = $logado ? ($_SESSION['email_usuario'] ?? 'usuario@beatstreet.com') : '';

$initials = "";
if ($logado) {
    $words = explode(" ", trim($username));
    $initials = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($words[0], 0, 2));
}

// --- Lógica de Busca ---
$searchTerm = isset($_GET['evento']) ? trim($_GET['evento']) : '';
$cidadeTerm = isset($_GET['cidade']) ? trim($_GET['cidade']) : '';

/**
 * PROBLEMA 1 RESOLVIDO: Validação robusta de caminhos físicos de imagens
 */
function getImagemFallback($caminho, $id_tipo) {
    if (!empty($caminho)) {
        $caminho_limpo = ltrim($caminho, './');
        $caminho_absoluto = __DIR__ . '/../' . $caminho_limpo;
        
        // Só retorna o caminho se o arquivo fisicamente existir no disco do servidor
        if (file_exists($caminho_absoluto)) {
            return '../' . $caminho_limpo;
        }
    }
    
    // Fallback seguro baseado na categoria do evento caso a imagem não exista ou seja nula
    switch ((int)$id_tipo) {
        case 1: return '../assets/img/computador1.jpg';
        case 2: return '../assets/img/computador2.jpg';
        case 3: return '../assets/img/computador3.jpg';
        case 4: return '../assets/img/computador4.jpg';
        default: return '../assets/img/computador1.jpg';
    }
}

try {
    /**
     * PROBLEMA 2 RESOLVIDO: Inclusão do filtro 'AND e.data_evento >= CURDATE()' 
     * para trazer apenas eventos de hoje em diante.
     */
    $sql = "SELECT e.*, t.nome_tipo, u.username as organizador
            FROM evento e
            INNER JOIN tipo_evento t ON e.id_tipo = t.id_tipo
            LEFT JOIN usuario u ON e.id_usuario = u.id_usuario
            WHERE (e.nome_evento LIKE :search OR t.nome_tipo LIKE :search)
              AND e.data_evento >= CURDATE()";
    
    $params = [':search' => '%' . $searchTerm . '%'];

    // Filtro de cidade complementar
    if (!empty($cidadeTerm) && $cidadeTerm !== 'gps') {
        $sql .= " AND (e.cidade = :cidade OR e.estado = :cidade)";
        $params[':cidade'] = $cidadeTerm;
    }

    // Nova Lógica de Ordenação: 
    // Prioriza e.nome_evento (peso 1) sobre t.nome_tipo (peso 2)
    $sql .= " ORDER BY 
                CASE 
                    WHEN e.nome_evento LIKE :search THEN 1 
                    ELSE 2 
                END ASC,
                e.data_evento ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $resultados = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Resultados para "<?= htmlspecialchars($searchTerm) ?>" - BeatStreet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styleDashboard.css">
</head>
<body class="busca-page">

<header class="header-sympla">
  <a href="../controllers/dashboard-process.php" class="logo">
      BEA<span class="roxo">T</span>S<span class="laranja">T</span>REET
  </a>
  <nav class="nav-links nav-desktop">
    <?php if ($logado): ?>
<?php if (!empty($_SESSION['documentos_completos'])): ?>
                      <a href="../controllers/evento-process.php" class="nav-item">
                          <svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor"/></svg> Criar evento
                      </a>
                  <?php else: ?>
                      <a href="#" class="nav-item" onclick="abrirModalDocumentos(event)">
                          <svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor"/></svg> Criar evento
                      </a>
                  <?php endif; ?>
        <a href="../views/meus-eventos.php" class="nav-item">
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
<li>
                  <?php if (!empty($_SESSION['documentos_completos'])): ?>
                      <a href="../controllers/evento-process.php">
                          <svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor"/></svg> Criar evento
                      </a>
                  <?php else: ?>
                      <a href="#" onclick="abrirModalDocumentos(event)">
                          <svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor"/></svg> Criar evento
                      </a>
                  <?php endif; ?>
              </li>
              <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z" fill="currentColor"/></svg> Meus eventos</a></li>
              <li class="divider"></li>
              <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z" fill="currentColor"/></svg> Suporte</a></li>
              <li class="divider"></li>
              <li><a href="../controllers/logout-process.php" class="logout-link"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" fill="currentColor"/></svg> Sair</a></li>
            </ul>
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
  
  <input type="text" id="inputBusca" name="evento" placeholder="Buscar eventos, artistas..." autocomplete="off" value="<?= htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8') ?>">
  
  <div id="containerSugestoes" class="search-suggestions"></div>
</div>

  <div class="location-box">
    <svg class="location-icon" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z" fill="currentColor"/></svg>
    <span id="locationSelectedText"><?= !empty($cidadeTerm) && $cidadeTerm !== 'gps' ? htmlspecialchars($cidadeTerm) : 'Qualquer lugar' ?></span>
    <input type="hidden" name="cidade" id="cidadeInput" value="<?= htmlspecialchars($cidadeTerm, ENT_QUOTES, 'UTF-8') ?>">
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

<main class="yt-results-container">
    <h2 class="search-title">Resultados para "<?= htmlspecialchars($searchTerm) ?>"</h2>

    <?php if (!empty($resultados)): ?>
        <?php foreach ($resultados as $ev): 
            $img = getImagemFallback($ev['imagem_evento'] ?? '', $ev['id_tipo']);
            $idEvento = $ev['id_evento'] ?? $ev['id'];
            $tipoEvento = $ev['nome_tipo'] ?? 'Evento';
            $dataEvento = isset($ev['data_evento']) ? date('d/m/Y \à\s H:i', strtotime($ev['data_evento'])) : 'Em breve';
            $cidade = $ev['cidade'] ?? 'Local a definir';
            $organizador = $ev['organizador'] ?? 'BeatStreet';
            $orgInitial = strtoupper(substr($organizador, 0, 1));
        ?>
        <a href="../controllers/detalhe-evento.php?id=<?= $idEvento ?>" class="yt-card">
            <div class="yt-thumbnail">
                <img src="<?= htmlspecialchars($img) ?>" alt="Capa do Evento">
                <span class="yt-badge"><?= htmlspecialchars($tipoEvento) ?></span>
            </div>

            <div class="yt-info">
                <h3 class="yt-title"><?= htmlspecialchars($ev['nome_evento']) ?></h3>
                
                <div class="yt-meta">
                    <span><?= htmlspecialchars($dataEvento) ?></span>
                    <span class="yt-meta-dot"></span>
                    <span><?= htmlspecialchars($cidade) ?></span>
                </div>

                <div class="yt-organizer">
                    <div class="yt-organizer-avatar"><?= $orgInitial ?></div>
                    <span><?= htmlspecialchars($organizador) ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-results">
            <svg viewBox="0 0 24 24" width="60" height="60" style="color: #444; margin-bottom: 20px;"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" fill="currentColor"/></svg>
            <h3>Nenhum evento encontrado</h3>
            <p>Tente buscar por outros termos, artistas ou categorias.</p>
        </div>
    <?php endif; ?>
</main>

<footer class="site-footer">
  <p>© 2026 BeatStreet</p>
</footer>

<script src="../assets/js/menu.js"></script>
<script src="../assets/js/app.js"></script>
</body>
</html>