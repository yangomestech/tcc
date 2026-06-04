<?php
// controllers/editar-evento.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado. Faça login.");
}

require_once '../config/.conexao.php';

$id_evento = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_evento) {
    die("ID de evento inválido.");
}

// Verifica se o evento existe e se o usuário logado é realmente o dono
$stmt = $conn->prepare("SELECT * FROM evento WHERE id_evento = :id_evento AND id_usuario = :id_usuario");
$stmt->execute([
    ':id_evento' => $id_evento,
    ':id_usuario' => $_SESSION['id_usuario']
]);

$evento_edit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evento_edit) {
    // Se caiu aqui, ou o evento não existe, ou o usuário tentou hackear a URL para editar evento alheio
    die("Acesso negado ou evento não encontrado. Você só pode editar os seus próprios eventos.");
}

// Busca os estilos que já estavam marcados neste evento para preencher os checkboxes
$stmtEstilos = $conn->prepare("SELECT id_estilo_danca FROM ligacao_evento_estilo WHERE id_evento = ?");
$stmtEstilos->execute([$id_evento]);
$estilos_selecionados = $stmtEstilos->fetchAll(PDO::FETCH_COLUMN); 
// Retorna um array simples [1, 3, 4]

// Carrega as tabelas auxiliares (mesma lógica do processo normal)
$tipos_evento = $conn->query("SELECT id_tipo, nome_tipo FROM tipo_evento ORDER BY nome_tipo")->fetchAll(PDO::FETCH_ASSOC);
$estilos_danca = $conn->query("SELECT id_estilo_danca, nome_estilo FROM estilo_danca ORDER BY nome_estilo")->fetchAll(PDO::FETCH_ASSOC);

// Carrega a view de criação, mas agora com os dados do $evento_edit na memória
require_once '../views/cadastro-evento.php';
?>