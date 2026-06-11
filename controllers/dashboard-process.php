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
        // Remove prefixos '../' ou '/' duplicados que existam na string do banco
        $caminho_limpo = ltrim($caminho, './');
        
        // Reconstrói o caminho absoluto exato para o sistema de ficheiros do Linux
        $caminho_absoluto = __DIR__ . '/../' . $caminho_limpo;
        
        if (file_exists($caminho_absoluto)) {
            // Retorna o caminho relativo uniforme padrão para as views HTML
            return "../" . $caminho_limpo; 
        }
    }
    
    // Fallback estático se o ficheiro físico não existir ou o registo for nulo
    switch($id_tipo) {
        case 1: return "../assets/img/computador1.jpg"; 
        case 2: return "../assets/img/computador2.jpg"; 
        case 3: return "../assets/img/computador3.jpg"; 
        case 4: return "../assets/img/computador4.jpg"; 
        default: return "../assets/img/computador1.jpg";
    }
}

$hoje = date('Y-m-d');

// DESTAQUES
$sqlDestaques = "SELECT e.*, COUNT(p.id_presenca) as total_presencas 
                 FROM evento e 
                 LEFT JOIN presenca p ON e.id_evento = p.id_evento 
                 WHERE e.data_evento >= :hoje 
                 GROUP BY e.id_evento 
                 ORDER BY total_presencas DESC, e.data_evento ASC 
                 LIMIT 5";
$stmtDestaques = $conn->prepare($sqlDestaques);
$stmtDestaques->execute([':hoje' => $hoje]);
$eventosCarrossel = $stmtDestaques->fetchAll(PDO::FETCH_ASSOC);

if(empty($eventosCarrossel)) {
    $stmtDestaques = $conn->prepare("SELECT * FROM evento WHERE data_evento >= :hoje ORDER BY data_evento ASC LIMIT 5");
    $stmtDestaques->execute([':hoje' => $hoje]);
    $eventosCarrossel = $stmtDestaques->fetchAll(PDO::FETCH_ASSOC);
}

// CORREÇÃO 2: INNER JOIN para puxar 'nome_tipo' e 'organizador_arroba' pro card
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

// CORREÇÃO 3: Destruindo o HTML hardcoded e chamando o componente
function renderRowEventos($titulo, $eventos, $isLogado) {
    if (empty($eventos)) return;
    echo "<section class='section'>";
    echo "<h2>" . htmlspecialchars($titulo) . "</h2>";
    echo "<div class='cards'>";
    
    foreach ($eventos as $ev) {
        // Resolve a imagem aqui e passa pro componente
        $imagem = getImagemFallback($ev['imagem_evento'] ?? '', $ev['id_tipo']);
        include __DIR__ . '/../views/components/card-evento.php';
    }
    
    echo "</div></section>";
}
$todosEventos = $stmtTodos->fetchAll(PDO::FETCH_ASSOC);


require_once __DIR__ . '/../views/dashboard.php';
?>