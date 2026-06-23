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
<body>

<header class="header-sympla">
  <a href="../controllers/dashboard-process.php" class="logo">
      BEA<span class="roxo">T</span>S<span class="laranja">T</span>REET
  </a>
  <nav class="nav-links nav-desktop">
    <?php if ($logado): ?>
        <a href="../controllers/evento-process.php" class="nav-item">Criar evento</a>
        <a href="../views/meus-eventos.php" class="nav-item">Meus eventos</a>
        <div class="user-menu-container">
          <button class="user-profile-btn" id="userMenuBtn">
            <svg viewBox="0 0 24 24" width="24" height="24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" fill="currentColor"/></svg>
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
              <li><a href="../controllers/logout-process.php" class="logout-link">Sair</a></li>
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
      <li class="location-item" data-value="">Qualquer lugar</li>
      <li class="location-item" data-value="SP">São Paulo (SP)</li>
      <li class="location-item" data-value="RJ">Rio de Janeiro (RJ)</li>
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

<footer>
  <p style="text-align: center; color: #666; padding: 20px;">© 2026 BeatStreet</p>
</footer>

<script src="../assets/js/menu.js"></script>
<script src="../assets/js/app.js"></script>
</body>
</html>