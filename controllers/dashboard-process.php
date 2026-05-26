<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /../views/login.php");
    exit();
}

// Conexão com o Banco de Dados (Certifique-se de incluir seu arquivo de conexão aqui)
 require_once __DIR__ . '/../config/.conexao.php';

$username = $_SESSION['username'] ?? 'Usuário';
$email = $_SESSION['email_usuario'] ?? 'usuario@beatstreet.com';

// Lógica para pegar as iniciais do usuário
$words = explode(" ", trim($username));
$initials = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($words[0], 0, 2));

// FUNÇÃO DE FALLBACK DE IMAGEM
function getImagemFallback($caminho, $id_tipo) {
    if (!empty($caminho) && file_exists("../" . $caminho)) {
        return "../" . $caminho;
    }
    switch($id_tipo) {
        case 1: return "https://images.unsplash.com/photo-1535525153412-5a42439a210d?q=80&w=800&auto=format&fit=crop"; // Dança
        case 2: return "https://images.unsplash.com/photo-1520975922323-3c36e27c0f06?q=80&w=800&auto=format&fit=crop"; // Jam
        case 3: return "https://images.unsplash.com/photo-1504609813442-a8924e83f76e?q=80&w=800&auto=format&fit=crop"; // Rima
        case 4: return "https://images.unsplash.com/photo-1521334884684-d80222895322?q=80&w=800&auto=format&fit=crop"; // Slam
        default: return "../assets/img/computador1.jpg";
    }
}

// BUSCAR DADOS DO USUÁRIO LOGADO
$stmtUser = $conn->prepare("SELECT cidade, estado FROM usuario WHERE id_usuario = :id");
$stmtUser->execute([':id' => $_SESSION['id_usuario']]);
$userLogado = $stmtUser->fetch(PDO::FETCH_ASSOC);
$cidadeUser = $userLogado['cidade'] ?? '';
$estadoUser = $userLogado['estado'] ?? '';

$hoje = date('Y-m-d');

// DESTAQUES (Carrossel)
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

// BUSCA GERAL PARA CATEGORIAS
$sqlTodos = "SELECT * FROM evento WHERE data_evento >= :hoje ORDER BY data_evento ASC";
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
    
    if ((!empty($cidadeUser) && strcasecmp($ev['cidade'], $cidadeUser) == 0) || 
        (!empty($estadoUser) && strcasecmp($ev['estado'], $estadoUser) == 0)) {
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

// Função Helper para desenhar as seções horizontais
function renderRowEventos($titulo, $eventos) {
    if (empty($eventos)) return;
    echo "<section class='section'>";
    echo "<h2>" . htmlspecialchars($titulo) . "</h2>";
    echo "<div class='cards'>";
    foreach ($eventos as $ev) {
        $imagem = htmlspecialchars(getImagemFallback($ev['imagem_evento'] ?? '', $ev['id_tipo']));
        $dataFmt = date('d/m', strtotime($ev['data_evento']));
        $cidade = htmlspecialchars($ev['cidade']);
        $nome = htmlspecialchars($ev['nome_evento']);
        $horario = substr($ev['horario_evento'] ?? '00:00:00', 0, 5);
        
        echo "
        <div class='card'>
            <img src='{$imagem}' alt='{$nome}'>
            <div class='card-content'>
                <h3>{$nome}</h3>
                <p>{$cidade} • {$dataFmt} às {$horario}</p>
                <button>Ver detalhes</button>
            </div>
        </div>";
    }
    echo "</div></section>";
}

// IMPORTANTE: Só chame a view DEPOIS de processar todas as variáveis acima.
require_once __DIR__ . '/../views/dashboard.php';
?>