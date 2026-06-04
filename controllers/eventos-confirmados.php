<?php
session_start();

// Segurança: Bloqueio rigoroso de não autenticados
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

require_once __DIR__ . '/../config/.conexao.php';

$id_usuario_logado = $_SESSION['id_usuario'];

// Lógica do Header
$username = $_SESSION['username'] ?? 'Usuário';
$email = $_SESSION['email_usuario'] ?? '';
$words = explode(" ", trim($username));
$initials = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($words[0], 0, 2));

// Busca Tipos para preencher o select do filtro
$tipos_evento = $conn->query("SELECT id_tipo, nome_tipo FROM tipo_evento ORDER BY nome_tipo")->fetchAll(PDO::FETCH_ASSOC);

// ==========================================
// CAPTURA DE FILTROS SEGUROS (GET)
// ==========================================
$busca = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS);
$filtro_tipo = filter_input(INPUT_GET, 'tipo', FILTER_VALIDATE_INT);

// ==========================================
// CONSTRUÇÃO DINÂMICA DA QUERY SQL
// ==========================================
$sql = "
    SELECT 
        e.*, 
        t.nome_tipo, 
        u.username AS organizador_arroba,
        (SELECT COUNT(*) FROM presenca p2 WHERE p2.id_evento = e.id_evento) AS total_presencas,
        GROUP_CONCAT(ed.nome_estilo SEPARATOR ',') AS estilos_danca
    FROM evento e
    INNER JOIN presenca p ON e.id_evento = p.id_evento
    INNER JOIN tipo_evento t ON e.id_tipo = t.id_tipo
    INNER JOIN usuario u ON e.id_usuario = u.id_usuario
    LEFT JOIN ligacao_evento_estilo lee ON e.id_evento = lee.id_evento
    LEFT JOIN estilo_danca ed ON lee.id_estilo_danca = ed.id_estilo_danca
    WHERE p.id_usuario = :id_usuario
";

$params = [':id_usuario' => $id_usuario_logado];

// Aplica filtros se existirem
if (!empty($busca)) {
    // Busca por Nome do Evento, Cidade ou Organizador
    $sql .= " AND (e.nome_evento LIKE :busca OR e.cidade LIKE :busca OR u.username LIKE :busca)";
    $params[':busca'] = "%{$busca}%";
}

if ($filtro_tipo) {
    $sql .= " AND e.id_tipo = :tipo";
    $params[':tipo'] = $filtro_tipo;
}

$sql .= " GROUP BY e.id_evento ORDER BY e.data_evento ASC, e.horario_evento ASC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $todos_eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar eventos confirmados: " . htmlspecialchars($e->getMessage()));
}

// ==========================================
// SEPARAÇÃO LÓGICA (FUTURO vs PASSADO)
// ==========================================
// Fazemos isso no PHP para economizar queries. A data de hoje sem horas.
$hoje = date('Y-m-d');

$eventos_futuros = [];
$eventos_passados = [];

foreach ($todos_eventos as $ev) {
    if ($ev['data_evento'] >= $hoje) {
        $eventos_futuros[] = $ev;
    } else {
        $eventos_passados[] = $ev;
    }
}

// Direciona para a View
require_once __DIR__ . '/../views/eventos-confirmados-view.php';
?>