<?php
function registrarLog($conn, $id_usuario, $id_produtor, $tipo_acao, $descricao)
{
    $ip_usuario = $_SERVER['REMOTE_ADDR'] ?? 'IP não identificado';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;

    $sql = "INSERT INTO logs (id_usuario, id_produtor, tipo_acao, descricao, ip_usuario, user_agent)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("iissss", $id_usuario, $id_produtor, $tipo_acao, $descricao, $ip_usuario, $user_agent);

    $resultado = $stmt->execute();
    $stmt->close();

    return $resultado;
}
?>