<?php
// Inicia a sessão para identificar o usuário
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Requisita a conexão com o banco de dados (saindo de controllers e indo para a raiz)
require_once __DIR__ . '/../config/.conexao.php';

// Captura o usuário logado (usando 1 como fallback de teste, igual fizemos na view)
$id_usuario_logado = $_SESSION['id_usuario'] ?? 1;

// Verifica se o ID do evento foi passado na URL (ex: excluir-evento.php?id=37)
$id_evento = $_GET['id'] ?? null;

if ($id_evento) {
    try {
        // Prepara a query de exclusão.
        // A trava de segurança AND id_usuario garante que o usuário só apague SEUS próprios eventos.
        $sql = "DELETE FROM evento WHERE id_evento = :id_evento AND id_usuario = :id_usuario";
        $stmt = $conn->prepare($sql);
        
        $stmt->execute([
            ':id_evento' => $id_evento,
            ':id_usuario' => $id_usuario_logado
        ]);

        // Verifica se alguma linha foi realmente afetada (se o evento existia e era dele)
        if ($stmt->rowCount() > 0) {
            // Excluído com sucesso! Redireciona de volta para a página de Meus Eventos
            header("Location: ../views/meus-eventos.php");
            exit;
        } else {
            // Se rowCount for 0, ou o evento não existe, ou não pertence a esse usuário
            echo "<script>
                    alert('Erro: Evento não encontrado ou você não tem permissão para excluí-lo.');
                    window.location.href = '../views/meus-eventos.php';
                  </script>";
            exit;
        }

    } catch (PDOException $e) {
        die("Erro ao tentar excluir o evento: " . $e->getMessage());
    }
} else {
    // Se tentarem acessar a página direto sem passar um ID na URL
    header("Location: ../views/meus-eventos.php");
    exit;
}
?>