<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteção de acesso: Apenas usuários logados
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

require_once __DIR__ . '/../config/.conexao.php';

// 1. Lógica do Menu do Usuário (Herdada do Dashboard para manter consistência)
$username = $_SESSION['username'] ?? 'Usuário';
$email = $_SESSION['email_usuario'] ?? 'usuario@beatstreet.com';

$words = explode(" ", trim($username));
$initials = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($words[0], 0, 2));

// 2. Validação estrita do ID passado via GET (Segurança contra SQL Injection e XSS)
$id_evento = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_evento) {
    // Se não for um número inteiro válido, redireciona de volta ao dashboard
    header("Location: dashboard-process.php");
    exit();
}

// 3. Consulta SQL Robusta (com INNER JOIN e LEFT JOIN + GROUP_CONCAT)
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

    // Se o evento não for encontrado no banco (ex: ID deletado ou inventado)
    if (!$evento) {
        header("Location: dashboard-process.php");
        exit();
    }
} catch (PDOException $e) {
    die("Erro ao buscar detalhes do evento: " . htmlspecialchars($e->getMessage()));
}

// 4. Formatação e Tratamento de Dados (Lógica de Imagem Simplificada)
$data_formatada = date('d/m/Y', strtotime($evento['data_evento']));
$horario_formatado = substr($evento['horario_evento'], 0, 5);

if (!empty($evento['imagem_evento'])) {
    $imagem_url = $evento['imagem_evento'];
} else {
    // Fallbacks baseados no tipo do evento se o banco retornar vazio
    switch($evento['id_tipo']) {
        case 1: $imagem_url = "https://images.unsplash.com/photo-1535525153412-5a42439a210d?q=80&w=800&auto=format&fit=crop"; break; 
        case 2: $imagem_url = "https://images.unsplash.com/photo-1520975922323-3c36e27c0f06?q=80&w=800&auto=format&fit=crop"; break; 
        case 3: $imagem_url = "https://images.unsplash.com/photo-1504609813442-a8924e83f76e?q=80&w=800&auto=format&fit=crop"; break; 
        case 4: $imagem_url = "https://images.unsplash.com/photo-1521334884684-d80222895322?q=80&w=800&auto=format&fit=crop"; break; 
        default: $imagem_url = "../assets/img/computador1.jpg"; break;
    }
}

// Montagem do endereço completo
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

// 5. Encaminha para a View
require_once __DIR__ . '/../views/detalhe-evento-view.php';
?>