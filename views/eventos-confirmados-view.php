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
                    <li><a href="../controllers/dashboard-process.php">Dashboard</a></li>
                    <li class="divider"></li>
                    <li><a href="../index.php?action=logout">Sair</a></li>
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
                    <?php require __DIR__ . '/components/card-evento.php'; // Inclusão isolada do card ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($eventos_passados)): ?>
            <h2 class="section-divider passados-titulo">Eventos Encerrados</h2>
            <div class="events-grid passados-grid">
                <?php foreach ($eventos_passados as $ev): ?>
                    <?php require __DIR__ . '/components/card-evento.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</main>

<script src="../assets/js/menu.js"></script>
</body>
</html>