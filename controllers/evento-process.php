<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado. Apenas usuários logados podem criar/editar eventos.");
}

require_once '../config/.conexao.php'; 

$mensagem = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Captura o campo oculto. Se existir e for número, é edição.
    $id_evento_edit = filter_input(INPUT_POST, 'id_evento_edit', FILTER_VALIDATE_INT);
    $is_update = !empty($id_evento_edit);
    
    $id_usuario_logado = $_SESSION['id_usuario'];

    // PROTEÇÃO CRÍTICA (IDOR): Se for update, garante que o usuário no POST é o dono no BD
    if ($is_update) {
        $checkStmt = $conn->prepare("SELECT id_usuario FROM evento WHERE id_evento = ?");
        $checkStmt->execute([$id_evento_edit]);
        $owner_id = $checkStmt->fetchColumn();

        if ($owner_id !== $id_usuario_logado) {
            die("Acesso negado: Tentativa de alteração em evento não autorizado.");
        }
    }

    $id_tipo        = filter_input(INPUT_POST, 'id_tipo', FILTER_VALIDATE_INT);
    $nome_evento    = trim($_POST['nome_evento']);
    $data_evento    = $_POST['data_evento'];
    $horario_evento = $_POST['horario_evento'];
    $descricao      = trim($_POST['descricao']);
    $mc_host        = !empty(trim($_POST['mc_host'])) ? trim($_POST['mc_host']) : null;
    $dj             = !empty(trim($_POST['dj'])) ? trim($_POST['dj']) : null;
    $cep            = trim($_POST['cep']);
    $estado         = trim($_POST['estado']);
    $cidade         = trim($_POST['cidade']);
    $bairro         = trim($_POST['bairro']);
    $rua            = trim($_POST['rua']);
    $numero         = trim($_POST['numero']);
    $complemento    = !empty(trim($_POST['complemento'])) ? trim($_POST['complemento']) : null;
    $estilos        = isset($_POST['estilos']) ? $_POST['estilos'] : [];
    
    $caminho_imagem = null;
    $atualizar_imagem = false; // Flag para saber se precisamos atualizar a coluna da imagem

    // LÓGICA DE UPLOAD
    if (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['imagem_evento']['name'], PATHINFO_EXTENSION));
        $formatos_permitidos = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($extensao, $formatos_permitidos)) {
            $novo_nome = uniqid('evento_') . '.' . $extensao;
            $diretorio_destino = '../uploads/eventos/';
            
            if (!is_dir($diretorio_destino)) { mkdir($diretorio_destino, 0755, true); }
            
            if (move_uploaded_file($_FILES['imagem_evento']['tmp_name'], $diretorio_destino . $novo_nome)) {
                $caminho_imagem = '../uploads/eventos/' . $novo_nome; 
                $atualizar_imagem = true;
            } else {
                $mensagem = "<div class='msg-erro'>Erro: O PHP não conseguiu mover o arquivo.</div>";
            }
        } else {
            $mensagem = "<div class='msg-erro'>Formato inválido. Use JPG, PNG ou WEBP.</div>";
        }
    } elseif (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] !== UPLOAD_ERR_NO_FILE) {
        $mensagem = "<div class='msg-erro'>Erro no envio: " . $_FILES['imagem_evento']['error'] . "</div>";
    }

    if(empty($mensagem)) {
        try {
            $conn->beginTransaction();

            if ($is_update) {
                // UPDATE Dinâmico (Só altera a imagem se o usuário subiu uma nova)
                $sqlEvento = "UPDATE evento SET 
                    id_tipo = :id_tipo, nome_evento = :nome_evento, data_evento = :data_evento, 
                    horario_evento = :horario_evento, descricao = :descricao, 
                    mc_host = :mc_host, dj = :dj, cep = :cep, estado = :estado, 
                    cidade = :cidade, bairro = :bairro, rua = :rua, numero = :numero, complemento = :complemento";
                
                if ($atualizar_imagem) { $sqlEvento .= ", imagem_evento = :imagem_evento"; }
                $sqlEvento .= " WHERE id_evento = :id_evento AND id_usuario = :id_usuario";

                $params = [
                    ':id_tipo'       => $id_tipo,
                    ':nome_evento'   => $nome_evento,
                    ':data_evento'   => $data_evento,
                    ':horario_evento'=> $horario_evento,
                    ':descricao'     => $descricao,
                    ':mc_host'       => $mc_host,
                    ':dj'            => $dj,
                    ':cep'           => $cep,
                    ':estado'        => $estado,
                    ':cidade'        => $cidade,
                    ':bairro'        => $bairro,
                    ':rua'           => $rua,
                    ':numero'        => $numero,
                    ':complemento'   => $complemento,
                    ':id_evento'     => $id_evento_edit,
                    ':id_usuario'    => $id_usuario_logado // Garante amarra no banco
                ];
                if ($atualizar_imagem) { $params[':imagem_evento'] = $caminho_imagem; }

                $stmt = $conn->prepare($sqlEvento);
                $stmt->execute($params);
                $id_evento_operado = $id_evento_edit;
                $msg_texto = "Evento atualizado com sucesso!";

            } else {
                // INSERT ORIGINAL MANTIDO
                $sqlEvento = "INSERT INTO evento (id_usuario, id_tipo, nome_evento, data_evento, horario_evento, descricao, imagem_evento, mc_host, dj, cep, estado, cidade, bairro, rua, numero, complemento) 
                              VALUES (:id_usuario, :id_tipo, :nome_evento, :data_evento, :horario_evento, :descricao, :imagem_evento, :mc_host, :dj, :cep, :estado, :cidade, :bairro, :rua, :numero, :complemento)";
                
                $stmt = $conn->prepare($sqlEvento);
                $stmt->execute([
                    ':id_usuario'    => $id_usuario_logado,
                    ':id_tipo'       => $id_tipo,
                    ':nome_evento'   => $nome_evento,
                    ':data_evento'   => $data_evento,
                    ':horario_evento'=> $horario_evento,
                    ':descricao'     => $descricao,
                    ':imagem_evento' => $caminho_imagem, 
                    ':mc_host'       => $mc_host,
                    ':dj'            => $dj,
                    ':cep'           => $cep,
                    ':estado'        => $estado,
                    ':cidade'        => $cidade,
                    ':bairro'        => $bairro,
                    ':rua'           => $rua,
                    ':numero'        => $numero,
                    ':complemento'   => $complemento
                ]);
                $id_evento_operado = $conn->lastInsertId();
                $msg_texto = "Evento publicado com sucesso na cena!";
            }

            // ATUALIZAÇÃO DOS ESTILOS: Deleta os antigos e recria (Mais eficiente e limpo)
            if ($is_update) {
                $delEstilos = $conn->prepare("DELETE FROM ligacao_evento_estilo WHERE id_evento = ?");
                $delEstilos->execute([$id_evento_operado]);
            }

            if (!empty($estilos)) {
                $sqlEstilo = "INSERT INTO ligacao_evento_estilo (id_evento, id_estilo_danca) VALUES (:id_evento, :id_estilo)";
                $stmtEstilo = $conn->prepare($sqlEstilo);
                foreach ($estilos as $id_estilo) {
                    $stmtEstilo->execute([':id_evento' => $id_evento_operado, ':id_estilo' => $id_estilo]);
                }
            }

            $conn->commit();
            $mensagem = "<div class='msg-sucesso'>{$msg_texto} <a href='detalhe-evento.php?id={$id_evento_operado}' style='color:inherit; text-decoration:underline;'>Ver evento</a></div>";

        } catch (Exception $e) {
            $conn->rollBack();
            $mensagem = "<div class='msg-erro'>Erro interno ao salvar. Verifique logs.</div>";
            error_log("Erro no DB (Evento): " . $e->getMessage()); 
        }
    }
}

// Consultas para popular o formulário caso seja acesso direto à tela de criação
$tipos_evento = $tipos_evento ?? $conn->query("SELECT id_tipo, nome_tipo FROM tipo_evento ORDER BY nome_tipo")->fetchAll(PDO::FETCH_ASSOC);
$estilos_danca = $estilos_danca ?? $conn->query("SELECT id_estilo_danca, nome_estilo FROM estilo_danca ORDER BY nome_estilo")->fetchAll(PDO::FETCH_ASSOC);

require_once '../views/cadastro-evento.php';
?>