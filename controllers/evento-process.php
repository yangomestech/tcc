<?php
session_start();

// 1. Verifica se está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

// =========================================================================
// 2. NOVA REGRA: BLOQUEIO PARA QUEM NÃO TEM RG E CPF
// =========================================================================
if (empty($_SESSION['documentos_completos'])) {
    // Salva uma mensagem de erro para exibir na tela do perfil
    $_SESSION['erro_documentos'] = "Acesso negado: Para criar um evento, é obrigatório preencher seu RG e CPF no perfil por motivos de segurança.";
    
    // Redireciona o usuário de volta para a tela de configurações da conta
    header("Location: ../views/usuario.php"); 
    exit();
}
// =========================================================================

require_once '../config/.conexao.php'; 

$mensagem = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_tipo        = $_POST['id_tipo'];
    $nome_evento    = $_POST['nome_evento'];
    $data_evento    = $_POST['data_evento'];
    $horario_evento = $_POST['horario_evento'];
    $descricao      = $_POST['descricao'];
    $mc_host        = !empty($_POST['mc_host']) ? $_POST['mc_host'] : null;
    $dj             = !empty($_POST['dj']) ? $_POST['dj'] : null;
    
    // Localização
    $cep         = $_POST['cep'];
    $estado      = $_POST['estado'];
    $cidade      = $_POST['cidade'];
    $bairro      = $_POST['bairro'];
    $rua         = $_POST['rua'];
    $numero      = $_POST['numero'];
    $complemento = !empty($_POST['complemento']) ? $_POST['complemento'] : null;

    $estilos = isset($_POST['estilos']) ? $_POST['estilos'] : [];
    $caminho_imagem = null;

// LÓGICA DE UPLOAD DE IMAGEM TRATADA E PREVENTIVA
    if (isset($_FILES['imagem_evento'])) {
        $error_code = $_FILES['imagem_evento']['error'];
        
        if ($error_code === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($_FILES['imagem_evento']['name'], PATHINFO_EXTENSION));
            $formatos_permitidos = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($extensao, $formatos_permitidos)) {
                $novo_nome = uniqid('evento_') . '.' . $extensao;
                $diretorio_destino = '../uploads/eventos/';
                
                if (!is_dir($diretorio_destino)) {
                    mkdir($diretorio_destino, 0755, true);
                }
                
                if (move_uploaded_file($_FILES['imagem_evento']['tmp_name'], $diretorio_destino . $novo_nome)) {
                    // Guarda estritamente o formato limpo, sem poluição de caminhos relativos
                    $caminho_imagem = 'uploads/eventos/' . $novo_nome; 
                } else {
                    $mensagem = "<div class='msg-erro'>Erro interno do servidor ao mover o ficheiro para o diretório de destino. Verifique as permissões de escrita da pasta.</div>";
                }
            } else {
                $mensagem = "<div class='msg-erro'>Formato de imagem inválido. Use apenas JPG, JPEG, PNG ou WEBP.</div>";
            }
        } elseif ($error_code !== UPLOAD_ERR_NO_FILE) {
            // Se não for 'UPLOAD_ERR_NO_FILE' (quando o utilizador simplesmente opta por não enviar foto), captura o erro do PHP
            switch ($error_code) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $mensagem = "<div class='msg-erro'>O ficheiro enviado excede o tamanho máximo permitido pelo servidor (verifique as diretivas do php.ini).</div>";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $mensagem = "<div class='msg-erro'>O upload do ficheiro foi feito apenas parcialmente. Tente novamente.</div>";
                    break;
                default:
                    $mensagem = "<div class='msg-erro'>Falha crítica no upload do ficheiro. Código do erro PHP: " . $error_code . "</div>";
                    break;
            }
        }
    }

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
            $mensagem = "<div class='msg-erro'>Erro ao salvar o evento: " . $e->getMessage() . "</div>";
        }
    }
}

$tipos_evento = $conn->query("SELECT id_tipo, nome_tipo FROM tipo_evento ORDER BY nome_tipo")->fetchAll(PDO::FETCH_ASSOC);
$estilos_danca = $conn->query("SELECT id_estilo_danca, nome_estilo FROM estilo_danca ORDER BY nome_estilo")->fetchAll(PDO::FETCH_ASSOC);

require_once '../views/cadastro-evento.php';
?>