<?php
// Inicia a sessão para capturar o usuário logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$logado = isset($_SESSION['id_usuario']);
$username = $logado ? ($_SESSION['username'] ?? 'Usuário') : 'Visitante';
$email = $logado ? ($_SESSION['email_usuario'] ?? 'usuario@beatstreet.com') : '';
/* ==========================================================================
   CONEXÃO COM O BANCO DE DADOS
   ========================================================================== */
require_once __DIR__ . '/../config/.conexao.php'; 

/* ==========================================================================
   IDENTIFICAÇÃO DO USUÁRIO LOGADO
   ========================================================================== */
$id_usuario_logado = $_SESSION['id_usuario'] ?? 1; 

/* ==========================================================================
   BUSCA DADOS DO USUÁRIO PARA O HEADER
   ========================================================================== */
$username = 'Usuário';
$initials = 'BS';

try {
    $sqlUser = "SELECT username, nome_usuario FROM usuario WHERE id_usuario = :id_usuario";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->execute([':id_usuario' => $id_usuario_logado]);
    $usuarioData = $stmtUser->fetch(PDO::FETCH_ASSOC);
    
    if ($usuarioData) {
        $username = $usuarioData['username'];
        $initials = strtoupper(substr($username, 0, 2));
    }
} catch (PDOException $e) {
    // Mantém os fallbacks caso dê erro
}

/* ==========================================================================
   FUNÇÃO PADRÃO DE FALLBACK DE IMAGEM (Unificada com o resto do sistema)
   ========================================================================== */
if (!function_exists('getImagemFallback')) {
    function getImagemFallback($caminho, $id_tipo) {
        if (!empty($caminho)) {
            $caminho_limpo = ltrim($caminho, './');
            $caminho_absoluto = __DIR__ . '/../' . $caminho_limpo;
            
            if (file_exists($caminho_absoluto)) {
                return "../" . $caminho_limpo; 
            }
        }
        
        switch((int)$id_tipo) {
            case 1: return "../assets/img/computador1.jpg"; 
            case 2: return "../assets/img/computador2.jpg"; 
            case 3: return "../assets/img/computador3.jpg"; 
            case 4: return "../assets/img/computador4.jpg"; 
            default: return "../assets/img/computador1.jpg";
        }
    }
}

/* ==========================================================================
   BUSCA DOS EVENTOS NO BANCO DE DADOS
   ========================================================================== */
try {
    $sql = "SELECT e.*, t.nome_tipo AS categoria 
            FROM evento e 
            INNER JOIN tipo_evento t ON e.id_tipo = t.id_tipo 
            WHERE e.id_usuario = :id_usuario 
            ORDER BY e.data_evento DESC, e.horario_evento DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_usuario' => $id_usuario_logado]);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao buscar eventos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Eventos - BeatStreet</title>
    <link rel="stylesheet" href="../assets/css/meus-eventos.css">
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
          <li><a href="../index.php?action=logout" class="logout-link"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" fill="currentColor"/></svg> Sair</a></li>
        </ul>
            </div>
        </div>
    </nav>
</header>

    <main class="container">
        
        <div class="page-header">
            <h1 class="page-title">Meus Eventos</h1>
            <p class="page-subtitle">Gerencie todos os eventos que você criou.</p>
        </div>

        <?php if (empty($eventos)): ?>
            <div class="empty-state">
                <h3>Nenhum evento encontrado</h3>
                <p>Você ainda não criou nenhum evento.</p>
                <a href="../controllers/evento-process.php" class="btn btn-criar">Criar Evento</a>
            </div>
        <?php else: ?>
            <div class="eventos-list">
                <?php foreach ($eventos as $evento): 
                    $dataFormatada = date('d/m/Y', strtotime($evento['data_evento']));
                    $horaFormatada = date('H:i', strtotime($evento['horario_evento']));
                    
                    // Invoca a função unificada de verificação de imagem
                    $imagemCapa = getImagemFallback($evento['imagem_evento'] ?? '', $evento['id_tipo']);
                ?>
                    <div class="evento-card">
                        
                        <img src="<?= htmlspecialchars($imagemCapa) ?>" alt="Capa do evento" class="evento-img">
                        
                        <div class="evento-content">
                            <div class="evento-info">
                                <h3><?= htmlspecialchars($evento['nome_evento']) ?></h3>
                                <div class="evento-details">
                                    <span><strong>Data e Hora:</strong> <?= $dataFormatada ?> às <?= $horaFormatada ?></span>
                                    <span><strong>Local:</strong> <?= htmlspecialchars($evento['cidade']) ?> - <?= htmlspecialchars($evento['estado']) ?></span>
                                    <span><strong>Categoria:</strong> <?=   htmlspecialchars($evento['categoria']) ?></span>
                                </div>
                            </div>
                            
                            <div class="evento-actions">
                                <a href="../controllers/detalhe-evento.php?id=<?= $evento['id_evento'] ?>" class="btn btn-detalhes">Ver Detalhes</a>
                                <a href="../controllers/editar-evento.php?id=<?= $evento['id_evento'] ?>" class="btn btn-editar">Editar</a>
                                <a href="../controllers/excluir-evento.php?id=<?= $evento['id_evento'] ?>" class="btn btn-excluir" onclick="return confirm('Tem certeza que deseja excluir o evento \'<?= htmlspecialchars(addslashes($evento['nome_evento'])) ?>\'?')">Excluir</a>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <script src="../assets/js/menu.js"></script>
</body>
</html>