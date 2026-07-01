<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ==========================================================================
   CONEXÃO COM O BANCO DE DADOS
   ========================================================================== */
require_once __DIR__ . '/../config/.conexao.php';

/* ==========================================================================
   IDENTIFICAÇÃO DO USUÁRIO LOGADO
   ========================================================================== */
$id_usuario_logado = $_SESSION['id_usuario'] ?? 1;

/* ==========================================================================
   DADOS DO USUÁRIO PARA O HEADER
   ========================================================================== */
$username = $_SESSION['username'] ?? 'Usuário';
$email = $_SESSION['email_usuario'] ?? 'usuario@beatstreet.com';
$initials = 'BS';

function gerarIniciaisUsuario($nome) {
    $nome = trim((string) $nome);

    if ($nome === '') {
        return 'BS';
    }

    $partes = preg_split('/\s+/', $nome);

    if (count($partes) >= 2) {
        return strtoupper(substr($partes[0], 0, 1) . substr($partes[1], 0, 1));
    }

    return strtoupper(substr($partes[0], 0, 2));
}

try {
    $sqlUser = "SELECT username, nome_usuario
                FROM usuario
                WHERE id_usuario = :id_usuario";

    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->execute([':id_usuario' => $id_usuario_logado]);
    $usuarioData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($usuarioData) {
        $username = $usuarioData['username'] ?? $usuarioData['nome_usuario'] ?? $username;
    }

    $initials = gerarIniciaisUsuario($username);

} catch (PDOException $e) {
    // Mantém os fallbacks caso dê erro
}

/* ==========================================================================
   FUNÇÃO PADRÃO DE FALLBACK DE IMAGEM
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

        switch ((int)$id_tipo) {
            case 1:
                return "../assets/img/computador1.jpg";
            case 2:
                return "../assets/img/computador2.jpg";
            case 3:
                return "../assets/img/computador3.jpg";
            case 4:
                return "../assets/img/computador4.jpg";
            default:
                return "../assets/img/computador1.jpg";
        }
    }
}

/* ==========================================================================
   BUSCA DOS EVENTOS + TOTAL DE PRESENÇAS
   ========================================================================== */
$eventos = [];
$presencasPorEvento = [];

try {
    $sql = "
        SELECT 
            e.*,
            t.nome_tipo AS categoria,
            COALESCE(pc.total_presencas, 0) AS total_presencas
        FROM evento e
        INNER JOIN tipo_evento t ON e.id_tipo = t.id_tipo
        LEFT JOIN (
            SELECT 
                id_evento,
                COUNT(*) AS total_presencas
            FROM presenca
            GROUP BY id_evento
        ) pc ON pc.id_evento = e.id_evento
        WHERE e.id_usuario = :id_usuario
        ORDER BY e.data_evento DESC, e.horario_evento DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_usuario' => $id_usuario_logado]);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($eventos as $evento) {
        $presencasPorEvento[(int)$evento['id_evento']] = [];
    }

    if (!empty($eventos)) {
        $idsEventos = array_map('intval', array_column($eventos, 'id_evento'));
        $placeholders = implode(',', array_fill(0, count($idsEventos), '?'));

        $sqlPresencas = "
            SELECT 
                p.id_evento,
                u.id_usuario,
                u.username,
                u.nome_usuario
            FROM presenca p
            INNER JOIN usuario u ON p.id_usuario = u.id_usuario
            WHERE p.id_evento IN ($placeholders)
            ORDER BY u.username ASC, u.nome_usuario ASC
        ";

        $stmtPresencas = $conn->prepare($sqlPresencas);
        $stmtPresencas->execute($idsEventos);
        $presencas = $stmtPresencas->fetchAll(PDO::FETCH_ASSOC);

        foreach ($presencas as $presenca) {
            $idEventoPresenca = (int)$presenca['id_evento'];

            $nomeBase = $presenca['username'] ?: $presenca['nome_usuario'] ?: 'Usuário';

            $presencasPorEvento[$idEventoPresenca][] = [
                'id_usuario' => (int)$presenca['id_usuario'],
                'username' => $presenca['username'] ?: 'usuario',
                'nome_usuario' => $presenca['nome_usuario'] ?: $nomeBase,
                'initials' => gerarIniciaisUsuario($nomeBase)
            ];
        }
    }

} catch (PDOException $e) {
    die("Erro ao buscar eventos: " . htmlspecialchars($e->getMessage()));
}

$presencasJson = json_encode(
    $presencasPorEvento,
    JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
);
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
    <div class="logo-group">
        <a href="../controllers/dashboard-process.php" class="logo">
            BEA<span class="roxo">T</span>S<span class="laranja">T</span>REET
        </a>

        <span class="producer-label">ÁREA DO PRODUTOR</span>
    </div>

    <nav class="nav-links nav-desktop">
        <div class="user-menu-container">
            <button class="user-profile-btn" id="userMenuBtn" type="button" aria-label="Abrir menu do usuário">
                <svg class="hamburger-icon" viewBox="0 0 24 24" width="24" height="24">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" fill="currentColor"/>
                </svg>

                <div class="user-initials">
                    <?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </button>

            <div class="user-dropdown" id="userDropdown">
                <div class="dropdown-header">
                    <div class="user-initials-large">
                        <?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8'); ?>
                    </div>

                    <div class="user-info">
                        <strong><?= htmlspecialchars(strtoupper($username), ENT_QUOTES, 'UTF-8'); ?></strong>
                        <span><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>

                <ul class="dropdown-list">
                    <li>
                        <a href="../views/usuario.php">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/>
                            </svg>
                            Minha conta
                        </a>
                    </li>

                    <li>
                        <a href="../controllers/favoritos-process.php">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
                            </svg>
                            Favoritos
                        </a>
                    </li>

                    <li>
                        <a href="../controllers/evento-process.php">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor"/>
                            </svg>
                            Criar evento
                        </a>
                    </li>

                    <li>
                        <a href="../views/meus-eventos.php">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z" fill="currentColor"/>
                            </svg>
                            Meus eventos
                        </a>
                    </li>

                    <li class="divider"></li>

                    <li>
                        <a href="#">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z" fill="currentColor"/>
                            </svg>
                            Suporte
                        </a>
                    </li>

                    <li class="divider"></li>

                    <li>
                        <a href="../controllers/logout-process.php" class="logout-link">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" fill="currentColor"/>
                            </svg>
                            Sair
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container">
    <div class="page-header">
        <h1 class="page-title">Meus Eventos</h1>
        <p class="page-subtitle">Gerencie os eventos que você criou e acompanhe quem confirmou presença.</p>
    </div>

    <?php if (empty($eventos)): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V9h14v10zm0-12H5V5h14v2z" fill="currentColor"/>
                </svg>
            </div>

            <h3>Nenhum evento encontrado</h3>
            <p>Você ainda não criou nenhum evento. Publique seu primeiro rolê na cena.</p>

            <a href="../controllers/evento-process.php" class="btn btn-criar">
                Criar Evento
            </a>
        </div>
    <?php else: ?>
        <div class="eventos-list">
            <?php foreach ($eventos as $evento): ?>
                <?php
                    $idEvento = (int)$evento['id_evento'];
                    $dataFormatada = date('d/m/Y', strtotime($evento['data_evento']));
                    $horaFormatada = date('H:i', strtotime($evento['horario_evento']));
                    $imagemCapa = getImagemFallback($evento['imagem_evento'] ?? '', $evento['id_tipo']);
                    $totalPresencas = (int)($evento['total_presencas'] ?? 0);
                ?>

                <article class="evento-card">
                    <div class="evento-image-wrapper">
                        <img
                            src="<?= htmlspecialchars($imagemCapa, ENT_QUOTES, 'UTF-8'); ?>"
                            alt="Capa do evento <?= htmlspecialchars($evento['nome_evento'], ENT_QUOTES, 'UTF-8'); ?>"
                            class="evento-img"
                        >
                    </div>

                    <div class="evento-content">
                        <div class="evento-info">
                            <div class="evento-title-row">
                                <h3><?= htmlspecialchars($evento['nome_evento'], ENT_QUOTES, 'UTF-8'); ?></h3>

                                <button
                                    type="button"
                                    class="presence-pill js-open-presencas-modal"
                                    data-evento-id="<?= $idEvento; ?>"
                                    data-evento-nome="<?= htmlspecialchars($evento['nome_evento'], ENT_QUOTES, 'UTF-8'); ?>"
                                >
                                    <svg viewBox="0 0 24 24" width="18" height="18">
                                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5C23 14.17 18.33 13 16 13z" fill="currentColor"/>
                                    </svg>

                                    <?= $totalPresencas; ?> presença<?= $totalPresencas === 1 ? '' : 's'; ?>
                                </button>
                            </div>

                            <div class="evento-details">
                                <span>
                                    <strong>Data e Hora:</strong>
                                    <?= $dataFormatada; ?> às <?= $horaFormatada; ?>
                                </span>

                                <span>
                                    <strong>Local:</strong>
                                    <?= htmlspecialchars($evento['cidade'], ENT_QUOTES, 'UTF-8'); ?> -
                                    <?= htmlspecialchars($evento['estado'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>

                                <span>
                                    <strong>Categoria:</strong>
                                    <?= htmlspecialchars($evento['categoria'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>
                        </div>

                        <div class="evento-actions">
                            <a
                                href="../controllers/detalhe-evento.php?id=<?= $idEvento; ?>"
                                class="btn btn-detalhes"
                            >
                                Ver Detalhes
                            </a>

                            <button
                                type="button"
                                class="btn btn-presencas js-open-presencas-modal"
                                data-evento-id="<?= $idEvento; ?>"
                                data-evento-nome="<?= htmlspecialchars($evento['nome_evento'], ENT_QUOTES, 'UTF-8'); ?>"
                            >
                                Ver Presenças
                            </button>

                            <a
                                href="../controllers/editar-evento.php?id=<?= $idEvento; ?>"
                                class="btn btn-editar"
                            >
                                Editar
                            </a>

                            <a
                                href="#"
                                class="btn btn-excluir js-open-delete-modal"
                                data-delete-url="../controllers/excluir-evento.php?id=<?= $idEvento; ?>"
                                data-evento-nome="<?= htmlspecialchars($evento['nome_evento'], ENT_QUOTES, 'UTF-8'); ?>"
                            >
                                Excluir
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<div id="modalPresencasEvento" class="modal-overlay" style="display: none;">
    <div class="modal-box modal-presencas-box">
        <div class="modal-icon modal-icon-presencas">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5C23 14.17 18.33 13 16 13z"/>
            </svg>
        </div>

        <h3>Presenças confirmadas</h3>

        <p>
            Evento:
            <strong id="nomeEventoPresencas"></strong>
        </p>

        <span id="contadorPresencasModal" class="contador-presencas-modal"></span>

        <div id="listaPresencasModal" class="lista-presencas-modal"></div>

        <div class="modal-actions modal-actions-single">
            <button type="button" id="btnFecharPresencas" class="btn-modal-cancel">
                Fechar
            </button>
        </div>
    </div>
</div>

<div id="modalExcluirEvento" class="modal-overlay" style="display: none;">
    <div class="modal-box">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
            </svg>
        </div>

        <h3>Excluir evento?</h3>

        <p>
            Você está prestes a excluir o evento
            <strong id="nomeEventoExcluir"></strong>.
            Essa ação não poderá ser desfeita.
        </p>

        <div class="modal-actions">
            <button type="button" id="btnCancelarExclusao" class="btn-modal-cancel">
                Cancelar
            </button>

            <button type="button" id="btnConfirmarExclusao" class="btn-modal-delete">
                Excluir evento
            </button>
        </div>
    </div>
</div>

<script>
    window.PRESENCAS_EVENTOS = <?= $presencasJson ?: '{}' ?>;
</script>

<script src="../assets/js/menu.js"></script>
<script src="../assets/js/meus-eventos.js?v=4"></script>
</body>
</html>