<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado. Apenas usuários logados podem criar eventos.");
}

// Certifique-se que este arquivo define a variável $conn
require_once '../config/.conexao.php'; 

$mensagem = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_tipo        = filter_input(INPUT_POST, 'id_tipo', FILTER_VALIDATE_INT);
    $nome_evento    = trim($_POST['nome_evento']);
    $data_evento    = $_POST['data_evento'];
    $horario_evento = $_POST['horario_evento'];
    $descricao      = trim($_POST['descricao']);
    $mc_host        = !empty(trim($_POST['mc_host'])) ? trim($_POST['mc_host']) : null;
    $dj             = !empty(trim($_POST['dj'])) ? trim($_POST['dj']) : null;
    
    // Localização
    $cep         = $_POST['cep'];
    $estado      = $_POST['estado'];
    $cidade      = $_POST['cidade'];
    $bairro      = $_POST['bairro'];
    $rua         = $_POST['rua'];
    $numero      = $_POST['numero'];
    $complemento = !empty(trim($_POST['complemento'])) ? trim($_POST['complemento']) : null;

$estilos = isset($_POST['estilos']) ? $_POST['estilos'] : [];
    $caminho_imagem = null;

    // 1. LÓGICA DE UPLOAD DE IMAGEM REVISADA
    if (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['imagem_evento']['name'], PATHINFO_EXTENSION));
        $formatos_permitidos = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($extensao, $formatos_permitidos)) {
            $novo_nome = uniqid('evento_') . '.' . $extensao;
            $diretorio_destino = '../uploads/eventos/';
            
            if (!is_dir($diretorio_destino)) {
                mkdir($diretorio_destino, 0755, true);
            }
            
            if (move_uploaded_file($_FILES['imagem_evento']['tmp_name'], $diretorio_destino . $novo_nome)) {
                // Alinhado com a simplificação: salvando o caminho que o controlador lê direto
                $caminho_imagem = '../uploads/eventos/' . $novo_nome; 
            } else {
                $mensagem = "<div class='msg-erro'>Erro: O PHP não conseguiu mover o arquivo para a pasta de destino.</div>";
            }
        } else {
            $mensagem = "<div class='msg-erro'>Formato de imagem inválido. Use JPG, PNG ou WEBP. Foi enviado: ." . htmlspecialchars($extensao) . "</div>";
        }
    } elseif (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Se houve um erro de upload que NÃO seja "nenhum arquivo enviado"
        $erro_upload = $_FILES['imagem_evento']['error'];
        $mensagem = "<div class='msg-erro'>Erro no envio do arquivo. Código do erro PHP: " . $erro_upload . "</div>";
    }


    // 3. EXECUÇÃO DO INSERT
    if(empty($mensagem)) {
        try {
            $conn->beginTransaction();

            $sqlEvento = "INSERT INTO evento 
                (id_usuario, id_tipo, nome_evento, data_evento, horario_evento, descricao, imagem_evento, mc_host, dj, cep, estado, cidade, bairro, rua, numero, complemento) 
                VALUES 
                (:id_usuario, :id_tipo, :nome_evento, :data_evento, :horario_evento, :descricao, :imagem_evento, :mc_host, :dj, :cep, :estado, :cidade, :bairro, :rua, :numero, :complemento)";
            
            $stmt = $conn->prepare($sqlEvento);
            $stmt->execute([
                ':id_usuario'    => $_SESSION['id_usuario'],
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

            $id_evento_gerado = $conn->lastInsertId();

            if (!empty($estilos)) {
                $sqlEstilo = "INSERT INTO ligacao_evento_estilo (id_evento, id_estilo_danca) VALUES (:id_evento, :id_estilo)";
                $stmtEstilo = $conn->prepare($sqlEstilo);
                foreach ($estilos as $id_estilo) {
                    $stmtEstilo->execute([
                        ':id_evento' => $id_evento_gerado,
                        ':id_estilo' => $id_estilo
                    ]);
                }
            }

            $conn->commit();
            $mensagem = "<div class='msg-sucesso'>Evento publicado com sucesso na cena!</div>";

        } catch (Exception $e) {
            $conn->rollBack();
            $mensagem = "<div class='msg-erro'>Erro interno ao salvar o evento. Tente novamente.</div>";
            // Loga o erro real no arquivo do servidor para debug
            error_log("Erro no DB (Evento): " . $e->getMessage()); 
        }
    }
}

// Consultas para popular o formulário (Garante que $conn está ativo)
$tipos_evento = $conn->query("SELECT id_tipo, nome_tipo FROM tipo_evento ORDER BY nome_tipo")->fetchAll(PDO::FETCH_ASSOC);
$estilos_danca = $conn->query("SELECT id_estilo_danca, nome_estilo FROM estilo_danca ORDER BY nome_estilo")->fetchAll(PDO::FETCH_ASSOC);

require_once '../views/cadastro-evento.php';
?>