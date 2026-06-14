<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/.conexao.php';

// Proteção da rota: impede acesso de usuários não autenticados
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$username = $_SESSION['username'] ?? 'Usuário';
$email = $_SESSION['email_usuario'] ?? '';

// Geração de iniciais para manutenção do layout do Header
$words = explode(" ", trim($username));
$initials = count($words) >= 2 
    ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) 
    : strtoupper(substr($words[0], 0, 2));

// Processamento de requisições assíncronas (AJAX) para remoção de favoritos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {
    header('Content-Type: application/json');
    $id_evento = filter_input(INPUT_POST, 'id_evento', FILTER_VALIDATE_INT);

    if (!$id_evento) {
        echo json_encode(['success' => false, 'message' => 'ID do evento inválido.']);
        exit;
    }

    try {
        $stmtDelete = $conn->prepare("DELETE FROM favoritos_evento WHERE id_usuario = :id_usuario AND id_evento = :id_evento");
        $stmtDelete->execute([
            ':id_usuario' => $id_usuario,
            ':id_evento' => $id_evento
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro interno ao processar exclusão.']);
    }
    exit;
}

// Fallback de imagens conforme regra estabelecida no Dashboard
function getImagemFallback($caminho, $id_tipo) {
    if (!empty($caminho)) {
        $caminho_limpo = ltrim($caminho, './');
        $caminho_absoluto = __DIR__ . '/../' . $caminho_limpo;
        if (file_exists($caminho_absoluto)) {
            return "../" . $caminho_limpo; 
        }
        if (filter_var($caminho, FILTER_VALIDATE_URL)) {
            return $caminho;
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

// Consulta principal: Retorna eventos favoritados com agregação de presenças confirmadas
try {
    $sqlFavoritos = "SELECT e.*, t.nome_tipo,
                     (SELECT COUNT(*) FROM presenca p WHERE p.id_evento = e.id_evento) as total_presencas
                     FROM favoritos_evento f
                     INNER JOIN evento e ON f.id_evento = e.id_evento
                     INNER JOIN tipo_evento t ON e.id_tipo = t.id_tipo
                     WHERE f.id_usuario = :id_usuario
                     ORDER BY f.data_favorito DESC";
                     
    $stmtFav = $conn->prepare($sqlFavoritos);
    $stmtFav->execute([':id_usuario' => $id_usuario]);
    $favoritosRaw = $stmtFav->fetchAll(PDO::FETCH_ASSOC);

    $eventosFavoritos = [];
    foreach ($favoritosRaw as $item) {
        $item['imagem_processada'] = getImagemFallback($item['imagem_evento'] ?? '', $item['id_tipo']);
        $item['data_formatada'] = date('d/m/Y', strtotime($item['data_evento']));
        $item['horario_formatado'] = date('H:i', strtotime($item['horario_evento']));
        $eventosFavoritos[] = $item;
    }
} catch (PDOException $e) {
    die("Erro crítico de banco de dados. Contate o administrador.");
}

// Injeção de dependências de dados na View dedicada
require_once __DIR__ . '/../views/favoritos-views.php';