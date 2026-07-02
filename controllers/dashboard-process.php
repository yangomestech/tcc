<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/.conexao.php';

$logado = isset($_SESSION['id_usuario']);

$cidadeUser = '';
$estadoUser = '';
$initials = '';
$username = '';

if ($logado) {
    $username = $_SESSION['username'] ?? 'Usuário';
    
    $words = explode(" ", trim($username));
    $initials = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($words[0], 0, 2));

    $stmtUser = $conn->prepare("SELECT cidade, estado FROM usuario WHERE id_usuario = :id");
    $stmtUser->execute([':id' => $_SESSION['id_usuario']]);
    $userLogado = $stmtUser->fetch(PDO::FETCH_ASSOC);
    
    if ($userLogado) {
        $cidadeUser = $userLogado['cidade'] ?? '';
        $estadoUser = $userLogado['estado'] ?? '';
    }
}

// FUNÇÃO DE FALLBACK DE IMAGEM CORRIGIDA E SANITIZADA
function getImagemFallback($caminho, $id_tipo) {
    if (!empty($caminho)) {
        $caminho_limpo = ltrim($caminho, './');
        $caminho_absoluto = __DIR__ . '/../' . $caminho_limpo;
        
        if (file_exists($caminho_absoluto)) {
            return "../" . $caminho_limpo; 
        }
    }
    
    switch($id_tipo) {
        case 1: return "../assets/img/computador1.jpg"; 
        case 2: return "../assets/img/computador2.jpg"; 
        case 3: return "../assets/img/computador3.jpg"; 
        case 4: return "../assets/img/computador4.jpg"; 
        default: return "../assets/img/computador1.jpg";
    }
}

$hoje = date('Y-m-d');

// CORREÇÃO 1: Adicionado INNER JOIN na query principal de Destaques
$sqlDestaques = "SELECT e.*, t.nome_tipo, COUNT(p.id_presenca) as total_presencas 
                 FROM evento e 
                 INNER JOIN tipo_evento t ON e.id_tipo = t.id_tipo
                 LEFT JOIN presenca p ON e.id_evento = p.id_evento 
                 WHERE e.data_evento >= :hoje 
                 GROUP BY e.id_evento, t.nome_tipo 
                 ORDER BY total_presencas DESC, e.data_evento ASC 
                 LIMIT 5";
$stmtDestaques = $conn->prepare($sqlDestaques);
$stmtDestaques->execute([':hoje' => $hoje]);
$eventosCarrossel = $stmtDestaques->fetchAll(PDO::FETCH_ASSOC);

// CORREÇÃO 2: Adicionado INNER JOIN na query de fallback de Destaques
if(empty($eventosCarrossel)) {
    $sqlFallbackDestaques = "SELECT e.*, t.nome_tipo 
                             FROM evento e 
                             INNER JOIN tipo_evento t ON e.id_tipo = t.id_tipo 
                             WHERE e.data_evento >= :hoje 
                             ORDER BY e.data_evento ASC 
                             LIMIT 5";
    $stmtDestaques = $conn->prepare($sqlFallbackDestaques);
    $stmtDestaques->execute([':hoje' => $hoje]);
    $eventosCarrossel = $stmtDestaques->fetchAll(PDO::FETCH_ASSOC);
}

// TODOS OS EVENTOS (Query já estava correta)
$sqlTodos = "SELECT e.*, t.nome_tipo, u.username as organizador_arroba,
             (SELECT COUNT(*) FROM presenca p WHERE p.id_evento = e.id_evento) as total_presencas
             FROM evento e 
             INNER JOIN tipo_evento t ON e.id_tipo = t.id_tipo 
             INNER JOIN usuario u ON e.id_usuario = u.id_usuario
             WHERE e.data_evento >= :hoje 
             ORDER BY e.data_evento ASC";
$stmtTodos = $conn->prepare($sqlTodos);
$stmtTodos->execute([':hoje' => $hoje]);
$todosEventos = $stmtTodos->fetchAll(PDO::FETCH_ASSOC);

$eventosHoje = [];
$eventosProximos = [];
$eventosDanca = [];
$eventosJam = [];
$eventosRima = [];
$eventosSlam = [];

foreach ($todosEventos as $ev) {
    if ($ev['data_evento'] == $hoje) { $eventosHoje[] = $ev; }
    
    if ($logado && ((!empty($cidadeUser) && strcasecmp($ev['cidade'], $cidadeUser) == 0) || 
        (!empty($estadoUser) && strcasecmp($ev['estado'], $estadoUser) == 0))) {
        $eventosProximos[] = $ev;
    }

    switch ($ev['id_tipo']) {
        case 1: $eventosDanca[] = $ev; break;
        case 2: $eventosJam[] = $ev; break;
        case 3: $eventosRima[] = $ev; break;
        case 4: $eventosSlam[] = $ev; break;
    }
}

if (empty($eventosProximos)) {
    $eventosProximos = array_slice($todosEventos, 0, 10);
}

// Componente de renderização das linhas de cards
function renderRowEventos($titulo, $eventos, $logado = false) {
    // Limita visualmente cada categoria a no máximo 10 eventos
    $eventos = array_slice($eventos ?? [], 0, 10);

    if (empty($eventos)) {
        return;
    }

    $rowId = 'event-row-' . substr(md5($titulo), 0, 8);
    ?>

    <section class="section event-section">
        <h2><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h2>

        <div class="event-row-carousel" data-event-carousel>
            <button 
                type="button" 
                class="row-arrow row-arrow-left" 
                data-carousel-prev
                aria-label="Voltar eventos de <?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>"
            >
                &#10094;
            </button>

            <div class="cards" id="<?= $rowId ?>">
                <?php foreach ($eventos as $ev): 
                    $idEvento = $ev['id_evento'] ?? $ev['id'] ?? '';
                    $nomeEvento = $ev['nome_evento'] ?? 'Evento sem nome';

                    $imagemEvento = function_exists('getImagemFallback')
                        ? getImagemFallback($ev['imagem_evento'] ?? '', $ev['id_tipo'] ?? null)
                        : ($ev['imagem_evento'] ?? '../assets/img/evento-padrao.jpg');

                    $dataEvento = !empty($ev['data_evento'])
                        ? date('d/m/Y', strtotime($ev['data_evento']))
                        : 'Data não definida';

                    $horaRaw = $ev['hora_evento'] ?? $ev['horario_evento'] ?? '';
                    $horaEvento = !empty($horaRaw)
                        ? date('H:i', strtotime($horaRaw))
                        : '';

                    $cidade = $ev['cidade'] ?? '';
                    $estado = $ev['estado'] ?? '';

                    $localizacao = trim($cidade . (!empty($estado) ? ' - ' . $estado : ''));
                    if ($localizacao === '') {
                        $localizacao = 'Local não informado';
                    }
                ?>

                    <article class="card">
                        <img 
                            src="<?= htmlspecialchars($imagemEvento, ENT_QUOTES, 'UTF-8') ?>" 
                            alt="<?= htmlspecialchars($nomeEvento, ENT_QUOTES, 'UTF-8') ?>"
                        >

                        <div class="card-content">
                            <h3><?= htmlspecialchars($nomeEvento, ENT_QUOTES, 'UTF-8') ?></h3>

                            <p class="card-date">
                                <?= htmlspecialchars($dataEvento, ENT_QUOTES, 'UTF-8') ?>
                                <?= $horaEvento ? ' às ' . htmlspecialchars($horaEvento, ENT_QUOTES, 'UTF-8') : '' ?>
                            </p>

                            <p class="card-location">
                                <?= htmlspecialchars($localizacao, ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <a 
                                href="../controllers/detalhe-evento.php?id=<?= urlencode($idEvento) ?>" 
                                class="card-action"
                            >
                                Ver detalhes
                            </a>
                        </div>
                    </article>

                <?php endforeach; ?>
            </div>

            <button 
                type="button" 
                class="row-arrow row-arrow-right" 
                data-carousel-next
                aria-label="Avançar eventos de <?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>"
            >
                &#10095;
            </button>
        </div>
    </section>

    <?php
}
// CORREÇÃO 3: Linha duplicada de fetchAll removida daqui para não quebrar a listagem inferior

require_once __DIR__ . '/../views/dashboard.php';
?>