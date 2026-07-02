<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/.conexao.php';

$queryParam = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($queryParam)) {
    echo json_encode([]);
    exit;
}

try {
    // Nova Lógica de Ordenação: 
    // Prioriza e.nome_evento (peso 1) sobre t.nome_tipo (peso 2)
    $sql = "SELECT e.id_evento, e.nome_evento, t.nome_tipo 
            FROM evento e
            INNER JOIN tipo_evento t ON e.id_tipo = t.id_tipo
            WHERE e.nome_evento LIKE :query 
               OR t.nome_tipo LIKE :query
            ORDER BY 
               CASE 
                   WHEN e.nome_evento LIKE :query THEN 1 
                   ELSE 2 
               END ASC,
               e.data_evento ASC
            LIMIT 6";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':query' => '%' . $queryParam . '%']);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultados);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>