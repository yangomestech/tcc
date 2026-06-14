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
        <a href="../controllers/eventos-confirmados.php" class="nav-item">
          <svg viewBox="0 0 24 24" width="20" height="20"><path d="M22 10V6c0-1.11-.9-2-2-2H4c-1.1 0-1.99.89-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2 7.74c-1.75-.8-3-2.58-3-4.74s1.25-3.94 3-4.74V6H4v2.26c1.75.8 3 2.58 3 4.74s-1.25 3.94-3 4.74V18h16v-.26z" fill="currentColor"/></svg>
          Eventos confirmados
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
          <li><a href="../views/usuario.php"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/></svg> Minha conta</a></li>
          <li><a href="../controllers/favoritos-process.php"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/></svg> Favoritos</a></li>
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

<main class="evento-wrapper">
    
    <section class="evento-banner-section">
        <div class="banner-container">
            <img src="<?= htmlspecialchars($imagem_url); ?>" alt="Cartaz de <?= htmlspecialchars($evento['nome_evento']); ?>" class="img-cartaz">
        </div>

        <?php if (!empty($evento['estilos_danca'])): ?>
            <?php 
                $estilos_array = array_filter(array_map('trim', explode(',', $evento['estilos_danca'])));
                if (!empty($estilos_array)):
            ?>
                <div class="estilos-secao-container">
                    <p class="estilos-titulo">Estilos de Dança 🔥</p>
                    <div class="estilos-tags-container">
                        <?php foreach ($estilos_array as $estilo): ?>
                            <span class="tag-estilo"><?= htmlspecialchars($estilo); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>

    <section class="evento-info-section">
        
        <div class="info-header" style="position: relative;">
            <span class="tag-tipo"><?= htmlspecialchars($evento['nome_tipo']); ?></span>
            
            <?php if ($evento['id_usuario'] === $_SESSION['id_usuario']): ?>
                <a href="../controllers/editar-evento.php?id=<?= $evento['id_evento'] ?>" class="btn-editar-flutuante" title="Editar Evento">
                    <svg viewBox="0 0 24 24" width="20" height="20">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" fill="currentColor"/>
                    </svg>
                    <span>Editar</span>
                </a>
            <?php endif; ?>

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

        <div class="info-box endereco-box">
            <h3>📍 Onde vai acontecer?</h3>
            <p><?= htmlspecialchars($endereco); ?></p>
        </div>

        <div class="acoes-box">
            <form id="form-presenca" method="POST" style="flex: 1; display: flex;">
                <input type="hidden" name="action" value="toggle_presenca">
                <button type="submit" class="btn btn-presenca <?= $is_presente ? 'ativo' : '' ?>">
                    <?= $is_presente ? 'Cancelar Presença' : 'Marcar Presença' ?> 
                    <span class="badge-contador">(<?= $total_presencas ?>)</span>
                </button>
            </form>

            <form id="form-favorito" method="POST" style="flex: 1; display: flex;">
                <input type="hidden" name="action" value="toggle_favorito">
                <button type="submit" class="btn btn-favorito <?= $is_favorito ? 'ativo' : '' ?>">
                    <svg viewBox="0 0 24 24" width="20" height="20">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="<?= $is_favorito ? '#c60cc6' : 'currentColor' ?>"/>
                    </svg>
                    <span><?= $is_favorito ? 'Favoritado' : 'Favoritar' ?></span>
                </button>
            </form>
        </div>

    </section> 
    
    <section class="evento-descricao-section">
        <div class="info-box descricao-box">
            <h3>Sobre o Evento</h3>
            <div class="texto-descricao">
                <?= nl2br(htmlspecialchars($evento['descricao'] ?? 'Nenhuma descrição fornecida pelo organizador.')); ?>
            </div>
        </div>
        
        <div class="info-box comentarios-box" style="margin-top: 20px;">
            <h3>Comentários (<?= count($comentarios) ?>)</h3>
            
            <form method="POST" class="form-comentario">
                <input type="hidden" name="action" value="comentar">
                <textarea name="texto_comentario" placeholder="Adicione um comentário..." required rows="3"></textarea>
                <button type="submit" class="btn btn-presenca">Enviar Comentário</button>
            </form>

            <div class="lista-comentarios">
                <?php if (empty($comentarios)): ?>
                    <p class="sem-comentarios">Nenhum comentário ainda. Seja o primeiro!</p>
                <?php else: ?>
                    <?php foreach ($comentarios as $c): ?>
                        <div class="comentario-item">
                            <div class="comentario-header">
                                <strong>@<?= htmlspecialchars($c['username'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <span><?= date('d/m/Y H:i', strtotime($c['data_comentario'])) ?></span>
                            </div>
                            <div class="comentario-body">
                                <?= nl2br(htmlspecialchars($c['comentario'], ENT_QUOTES, 'UTF-8')) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

</main>

<footer>
    <p>© 2026 BeatStreet - Todos os direitos reservados</p>
</footer>

<script>
// ==========================================
// FETCH API - Ações sem recarregar a página
// ==========================================

async function handleAjaxForm(formId, callbackSucesso) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault(); 
        
        const btn = this.querySelector('button');
        const originalHtml = btn.innerHTML; 
        
        btn.style.opacity = '0.6';
        btn.style.pointerEvents = 'none';

        try {
            const formData = new FormData(this);
            
            const response = await fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (!response.ok) throw new Error('Falha na requisição');
            
            const data = await response.json();
            
            if (data.status === 'success') {
                callbackSucesso(btn, data);
            }
        } catch (error) {
            console.error(error);
            btn.innerHTML = originalHtml;
            alert('Ocorreu um erro ao processar sua ação. Tente novamente.');
        } finally {
            btn.style.opacity = '1';
            btn.style.pointerEvents = 'auto';
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    handleAjaxForm('form-presenca', (btn, data) => {
        if (data.is_presente) {
            btn.classList.add('ativo');
            btn.innerHTML = `Cancelar Presença <span class="badge-contador">(${data.total_presencas})</span>`;
        } else {
            btn.classList.remove('ativo');
            btn.innerHTML = `Marcar Presença <span class="badge-contador">(${data.total_presencas})</span>`;
        }
    });

    handleAjaxForm('form-favorito', (btn, data) => {
        const spanText = btn.querySelector('span');
        const svgPath = btn.querySelector('svg path');
        
        if (data.is_favorito) {
            btn.classList.add('ativo');
            spanText.textContent = 'Favoritado';
            svgPath.setAttribute('fill', '#c60cc6');
        } else {
            btn.classList.remove('ativo');
            spanText.textContent = 'Favoritar';
            svgPath.setAttribute('fill', 'currentColor');
        }
    });
});
</script>

<script src="../assets/js/menu.js"></script>
</body>
</html>