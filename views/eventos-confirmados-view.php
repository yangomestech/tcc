<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Eventos Confirmados - BeatStreet</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styleDashboard.css"> 
    <link rel="stylesheet" href="../assets/css/styleConfirmados.css">
</head>
<body>

<header class="header-sympla">
    <a href="../controllers/dashboard-process.php" class="logo">
        BEA<span class="roxo">T</span>S<span class="laranja">T</span>REET
    </a>
    
    <nav class="nav-links nav-desktop">
        <a href="../controllers/evento-process.php" class="nav-item">Criar evento</a>
        <a href="../controllers/eventos-confirmados.php" class="nav-item roxo">Meus eventos</a>

        <div class="user-menu-container">
            <button class="user-profile-btn" id="userMenuBtn">
                <div class="user-initials"><?= htmlspecialchars($initials); ?></div>
            </button>
            <div class="user-dropdown" id="userDropdown">
                <div class="dropdown-header">
                    <div class="user-info">
                        <strong><?= htmlspecialchars(strtoupper($username), ENT_QUOTES, 'UTF-8'); ?></strong>
                    </div>
                </div>
        <ul class="dropdown-list">
          <li><a href="../views/usuario.php"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/></svg> Minha conta</a></li>
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

<main class="confirmados-wrapper">
    
    <section class="page-header">
        <h1>Meus Eventos Confirmados</h1>
        <p>Acompanhe a agenda das batalhas e jams em que você marcou presença.</p>
    </section>

    <section class="filter-section">
        <form method="GET" action="eventos-confirmados.php" class="filter-form">
            <input type="text" name="q" placeholder="Buscar por nome, cidade ou organizador..." value="<?= htmlspecialchars($busca ?? '') ?>">
            
            <select name="tipo">
                <option value="">Todas as Categorias</option>
                <?php foreach ($tipos_evento as $tipo): ?>
                    <option value="<?= $tipo['id_tipo'] ?>" <?= ($filtro_tipo == $tipo['id_tipo']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipo['nome_tipo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit" class="btn-filtrar">Filtrar</button>
            <?php if(!empty($busca) || !empty($filtro_tipo)): ?>
                <a href="eventos-confirmados.php" class="btn-limpar">Limpar</a>
            <?php endif; ?>
        </form>
    </section>

    <?php if (empty($todos_eventos)): ?>
        <div class="empty-state">
            <svg viewBox="0 0 24 24" width="80" height="80"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-7-2h2v-2h-2v2zm0-4h2V8h-2v5z" fill="var(--roxo)"/></svg>
            <h2>Nenhum evento na sua agenda</h2>
            <p><?= (!empty($busca) || !empty($filtro_tipo)) ? 'Nenhum evento encontrado com esses filtros.' : 'Você ainda não confirmou presença em nenhum evento da cena.' ?></p>
            <a href="dashboard-process.php" class="btn-primary">Procurar Eventos</a>
        </div>
    <?php else: ?>

        <?php if (!empty($eventos_futuros)): ?>
            <h2 class="section-divider">Próximos Eventos</h2>
            <div class="events-grid">
                <?php foreach ($eventos_futuros as $ev): ?>
                    <?php 
                        // Correção: Executa a atribuição estritamente dentro da tag PHP
                        $imagem = $ev['imagem_render']; 
                        require __DIR__ . '/components/card-evento.php'; 
                    ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($eventos_passados)): ?>
            <h2 class="section-divider passados-titulo">Eventos Encerrados</h2>
            <div class="events-grid passados-grid">
                <?php foreach ($eventos_passados as $ev): ?>
                    <?php 
                        // Correção: Garante que a imagem também é injetada nos eventos antigos
                        $imagem = $ev['imagem_render']; 
                        require __DIR__ . '/components/card-evento.php'; 
                    ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</main>

<script src="../assets/js/menu.js"></script>
</body>
</html>