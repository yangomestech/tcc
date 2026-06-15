<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

require_once __DIR__ . '/../config/.conexao.php';

$id_usuario_logado = $_SESSION['id_usuario'];
$id_evento = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_evento) {
    header("Location: dashboard-process.php");
    exit();
}

// 1. Processamento POST (Suporta fluxo normal e AJAX via Fetch API)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Detecta se a requisição veio do JavaScript (esperando JSON)
    $isAjax = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

    try {
        if ($action === 'toggle_presenca') {
            $stmt = $conn->prepare("SELECT id_presenca FROM presenca WHERE id_usuario = :uid AND id_evento = :eid");
            $stmt->execute([':uid' => $id_usuario_logado, ':eid' => $id_evento]);
            
            if ($stmt->fetch()) {
                $stmtDel = $conn->prepare("DELETE FROM presenca WHERE id_usuario = :uid AND id_evento = :eid");
                $stmtDel->execute([':uid' => $id_usuario_logado, ':eid' => $id_evento]);
            } else {
                $stmtIns = $conn->prepare("INSERT IGNORE INTO presenca (id_usuario, id_evento) VALUES (:uid, :eid)");
                $stmtIns->execute([':uid' => $id_usuario_logado, ':eid' => $id_evento]);
            }

            // Se for AJAX, não redireciona. Responde com JSON e finaliza a execução.
            if ($isAjax) {
                // Recalcula o status atualizado
                $stmtPres = $conn->prepare("SELECT 1 FROM presenca WHERE id_usuario = ? AND id_evento = ?");
                $stmtPres->execute([$id_usuario_logado, $id_evento]);
                $is_presente = (bool) $stmtPres->fetchColumn();

                $stmtTotalPres = $conn->prepare("SELECT COUNT(*) FROM presenca WHERE id_evento = ?");
                $stmtTotalPres->execute([$id_evento]);
                $total_presencas = $stmtTotalPres->fetchColumn();

                echo json_encode(['status' => 'success', 'is_presente' => $is_presente, 'total_presencas' => $total_presencas]);
                exit();
            }

        } elseif ($action === 'toggle_favorito') {
            $stmt = $conn->prepare("SELECT id_favorito FROM favoritos_evento WHERE id_usuario = :uid AND id_evento = :eid");
            $stmt->execute([':uid' => $id_usuario_logado, ':eid' => $id_evento]);
            
            if ($stmt->fetch()) {
                $stmtDel = $conn->prepare("DELETE FROM favoritos_evento WHERE id_usuario = :uid AND id_evento = :eid");
                $stmtDel->execute([':uid' => $id_usuario_logado, ':eid' => $id_evento]);
            } else {
                $stmtIns = $conn->prepare("INSERT IGNORE INTO favoritos_evento (id_usuario, id_evento) VALUES (:uid, :eid)");
                $stmtIns->execute([':uid' => $id_usuario_logado, ':eid' => $id_evento]);
            }

            if ($isAjax) {
                $stmtFav = $conn->prepare("SELECT 1 FROM favoritos_evento WHERE id_usuario = ? AND id_evento = ?");
                $stmtFav->execute([$id_usuario_logado, $id_evento]);
                $is_favorito = (bool) $stmtFav->fetchColumn();

                echo json_encode(['status' => 'success', 'is_favorito' => $is_favorito]);
                exit();
            }

        } elseif ($action === 'comentar') {
            $comentario = trim($_POST['texto_comentario'] ?? '');
            if (!empty($comentario)) {
                $stmtIns = $conn->prepare("INSERT INTO comentario_evento (id_usuario, id_evento, comentario) VALUES (:uid, :eid, :comentario)");
                $stmtIns->execute([':uid' => $id_usuario_logado, ':eid' => $id_evento, ':comentario' => $comentario]);
            }
        }

        // Redirecionamento padrão para requisições não-AJAX (Fallback)
        if (!$isAjax) {
            header("Location: detalhe-evento.php?id=" . $id_evento);
            exit();
        }

    } catch (PDOException $e) {
        if ($isAjax) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro interno do servidor.']);
            exit();
        }
        error_log("Erro de banco na ação do evento: " . $e->getMessage());
    }
}

// 2. BUSCA DE DADOS PARA A VIEW
$username = $_SESSION['username'] ?? 'Usuário';
$email = $_SESSION['email_usuario'] ?? 'usuario@beatstreet.com';
$words = explode(" ", trim($username));

// Correção do Bug de Iniciais: substr(texto, INICIO, QUANTIDADE)
$initials = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($words[0], 0, 2));

// Busca os dados do Evento (Sem alterações, query original mantida)
$sql = "
    SELECT 
        e.*, 
        t.nome_tipo, 
        u.nome_usuario AS organizador_nome,
        u.username AS organizador_arroba,
        GROUP_CONCAT(ed.nome_estilo SEPARATOR ', ') AS estilos_danca
    FROM evento e
    INNER JOIN tipo_evento t ON e.id_tipo = t.id_tipo
    INNER JOIN usuario u ON e.id_usuario = u.id_usuario
    LEFT JOIN ligacao_evento_estilo lee ON e.id_evento = lee.id_evento
    LEFT JOIN estilo_danca ed ON lee.id_estilo_danca = ed.id_estilo_danca
    WHERE e.id_evento = :id_evento
    GROUP BY e.id_evento
";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_evento' => $id_evento]);
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evento) {
        header("Location: dashboard-process.php");
        exit();
    }

    // --- NOVAS CONSULTAS: Estados e Contagens ---

    // 1. Verifica se o usuário logado marcou presença
    $stmtPres = $conn->prepare("SELECT 1 FROM presenca WHERE id_usuario = ? AND id_evento = ?");
    $stmtPres->execute([$id_usuario_logado, $id_evento]);
    $is_presente = (bool) $stmtPres->fetchColumn();

    // 2. Total de presenças no evento
    $stmtTotalPres = $conn->prepare("SELECT COUNT(*) FROM presenca WHERE id_evento = ?");
    $stmtTotalPres->execute([$id_evento]);
    $total_presencas = $stmtTotalPres->fetchColumn();

    // 3. Verifica se o usuário logado favoritou
    $stmtFav = $conn->prepare("SELECT 1 FROM favoritos_evento WHERE id_usuario = ? AND id_evento = ?");
    $stmtFav->execute([$id_usuario_logado, $id_evento]);
    $is_favorito = (bool) $stmtFav->fetchColumn();

    // 4. Busca todos os comentários do evento (JOIN com a tabela de usuário para pegar o nome)
    $stmtComentarios = $conn->prepare("
        SELECT c.comentario, c.data_comentario, u.username, u.nome_usuario 
        FROM comentario_evento c 
        INNER JOIN usuario u ON c.id_usuario = u.id_usuario 
        WHERE c.id_evento = ? 
        ORDER BY c.data_comentario DESC
    ");
    $stmtComentarios->execute([$id_evento]);
    $comentarios = $stmtComentarios->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao buscar detalhes do evento: " . htmlspecialchars($e->getMessage()));
}

// Formatação
$data_formatada = date('d/m/Y', strtotime($evento['data_evento']));
$horario_formatado = substr($evento['horario_evento'], 0, 5);

// =================================================================================
// CORREÇÃO: Função de Validação Física de Imagem (Padronizada com o Dashboard)
// =================================================================================
if (!function_exists('getImagemFallback')) {
    function getImagemFallback($caminho, $id_tipo) {
        if (!empty($caminho)) {
            // Limpa barras dúbias para montar o path exato
            $caminho_limpo = ltrim($caminho, './');
            // Valida fisicamente a existência do arquivo no disco
            $caminho_absoluto = __DIR__ . '/../' . $caminho_limpo;
            
            if (file_exists($caminho_absoluto)) {
                return "../" . $caminho_limpo; 
            }
        }
        
        // Se a foto física não existe, aplica o fallback temático e à prova de falhas
        switch((int)$id_tipo) {
            case 1: return "../assets/img/computador1.jpg"; 
            case 2: return "../assets/img/computador2.jpg"; 
            case 3: return "../assets/img/computador3.jpg"; 
            case 4: return "../assets/img/computador4.jpg"; 
            default: return "../assets/img/computador1.jpg";
        }
    }
}

// Invoca a função corrigida
$imagem_url = getImagemFallback($evento['imagem_evento'] ?? '', $evento['id_tipo']);
// =================================================================================

$endereco = sprintf(
    "%s, %s %s - %s, %s - %s. CEP: %s",
    $evento['rua'],
    $evento['numero'],
    (!empty($evento['complemento']) ? "(" . $evento['complemento'] . ")" : ""),
    $evento['bairro'],
    $evento['cidade'],
    strtoupper($evento['estado']),
    $evento['cep']
);

require_once __DIR__ . '/../views/detalhe-evento-view.php';
?>