<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($evento['nome_evento']); ?> - BeatStreet</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styleDashboard.css"> 
    <link rel="stylesheet" href="../assets/css/styleDetalheEvento.css">
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

        <div class="user-menu-container">
            <button class="user-profile-btn" id="userMenuBtn">
                <svg class="hamburger-icon" viewBox="0 0 24 24" width="24" height="24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" fill="currentColor"/></svg>
                <div class="user-initials"><?= htmlspecialchars($initials); ?></div>
            </button>

            <div class="user-dropdown" id="userDropdown">
                <div class="dropdown-header">
                    <div class="user-initials-large"><?= htmlspecialchars($initials); ?></div>
                    <div class="user-info">
                        <strong><?= htmlspecialchars(strtoupper($username), ENT_QUOTES, 'UTF-8'); ?></strong>
                        <span><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>
                <ul class="dropdown-list">
                    <li><a href="../controllers/dashboard-process.php">Voltar ao Dashboard</a></li>
                    <li class="divider"></li>
                    <li><a href="../index.php?action=logout" class="logout-link">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main class="evento-wrapper">
    
    <section class="evento-banner-section">
        <div class="banner-container">
            <img src="<?= htmlspecialchars($imagem_url); ?>" alt="Cartaz de <?= htmlspecialchars($evento['nome_evento']); ?>" class="img-cartaz">
        </div>
    </section>

    <section class="evento-info-section">
        
        <div class="info-header">
            <span class="tag-tipo"><?= htmlspecialchars($evento['nome_tipo']); ?></span>
            <h1 class="evento-titulo"><?= htmlspecialchars($evento['nome_evento']); ?></h1>
            <p class="evento-organizador">Organizado por: <strong>@<?= htmlspecialchars($evento['organizador_arroba']); ?></strong></p>
        </div>

        <div class="info-box data-hora-box">
            <div class="item">
                <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z" fill="currentColor"/></svg>
                <div>
                    <span class="label">Data</span>
                    <span class="valor"><?= htmlspecialchars($data_formatada); ?></span>
                </div>
            </div>
            <div class="item">
                <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" fill="currentColor"/></svg>
                <div>
                    <span class="label">Horário</span>
                    <span class="valor"><?= htmlspecialchars($horario_formatado); ?> hrs</span>
                </div>
            </div>
        </div>

        <?php if (!empty($evento['mc_host']) || !empty($evento['dj'])): ?>
        <div class="info-box cultura-box">
            <h3>Atrações da Line-up</h3>
            <?php if (!empty($evento['mc_host'])): ?>
                <p>🎙️ <strong>MC / Host:</strong> <?= htmlspecialchars($evento['mc_host']); ?></p>
            <?php endif; ?>
            <?php if (!empty($evento['dj'])): ?>
                <p>🎧 <strong>DJ:</strong> <?= htmlspecialchars($evento['dj']); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($evento['estilos_danca'])): ?>
        <div class="info-box danca-box">
            <h3>Estilos de Dança</h3>
            <p class="estilos-tags">🔥 <?= htmlspecialchars($evento['estilos_danca']); ?></p>
        </div>
        <?php endif; ?>

        <div class="info-box endereco-box">
            <h3>📍 Onde vai acontecer?</h3>
            <p><?= htmlspecialchars($endereco); ?></p>
        </div>

        <div class="acoes-box">
            <button class="btn btn-presenca">Marcar Presença</button>
            <button class="btn btn-favorito">
                <svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/></svg>
                Favoritar
            </button>
        </div>

    </section>

    <section class="evento-descricao-section">
        <div class="info-box descricao-box">
            <h3>Sobre o Evento</h3>
            <div class="texto-descricao">
                <?= nl2br(htmlspecialchars($evento['descricao'] ?? 'Nenhuma descrição fornecida pelo organizador.')); ?>
            </div>
        </div>
    </section>

</main>

<footer>
    <p>© 2026 BeatStreet - Todos os direitos reservados</p>
</footer>

<script src="../assets/js/menu.js"></script>
</body>
</html>