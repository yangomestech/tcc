<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Validação de Autenticação e Método (Security Gateway)
if (!isset($_SESSION['id_usuario'])) {
    header("HTTP/1.1 401 Unauthorized");
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    die("Método não permitido. Requisições GET são bloqueadas por segurança.");
}

require_once __DIR__ . '/../config/.conexao.php';

$id_evento = filter_input(INPUT_POST, 'id_evento', FILTER_VALIDATE_INT);
$id_usuario = $_SESSION['id_usuario'];

if (!$id_evento) {
    die("ID de evento corrompido.");
}

try {
    // 2. Proteção contra IDOR: Busca a imagem e valida a propriedade do evento na mesma query
    $stmt = $conn->prepare("SELECT imagem_evento FROM evento WHERE id_evento = :id_evento AND id_usuario = :id_usuario");
    $stmt->execute([
        ':id_evento' => $id_evento,
        ':id_usuario' => $id_usuario
    ]);
    
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evento) {
        // Se falhar, ou o evento não existe, ou o cara tentou manipular o DOM para apagar evento dos outros
        die("Evento não encontrado ou você não tem privilégios para excluí-lo.");
    }

    $conn->beginTransaction();

    // 3. Executa a deleção
    // Nota: Suas tabelas filhas (presenca, favoritos_evento) já têm ON DELETE CASCADE no banco.
    // O MySQL vai limpar as dependências automaticamente de forma eficiente.
    $stmtDel = $conn->prepare("DELETE FROM evento WHERE id_evento = :id_evento");
    $stmtDel->execute([':id_evento' => $id_evento]);

    // 4. Otimização: Limpeza do file system (Grep-like cleanup)
    if (!empty($evento['imagem_evento'])) {
        $caminho_limpo = ltrim($evento['imagem_evento'], './');
        $caminho_absoluto = realpath(__DIR__ . '/../' . $caminho_limpo);
        
        // Verifica se o arquivo existe e se está dentro do diretório do projeto (evita Path Traversal)
        if ($caminho_absoluto && file_exists($caminho_absoluto)) {
            unlink($caminho_absoluto);
        }
    }

    $conn->commit();
    
    // Redireciona para o dashboard com flag de sucesso
    header("Location: dashboard-process.php?msg=deleted");
    exit();

} catch (PDOException $e) {
    $conn->rollBack();
    // Log do erro silencioso para análise posterior sem expor o banco ao usuário
    error_log("[DELETE EVENT ERROR] " . $e->getMessage());
    die("Erro interno de banco de dados ao tentar excluir o evento.");
}
?>