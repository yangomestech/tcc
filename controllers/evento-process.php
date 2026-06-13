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
    
    $id_tipo        = (int) $_POST['id_tipo']; // Cast explícito para inteiro
    $nome_evento    = $_POST['nome_evento'];
    $data_evento    = $_POST['data_evento'];
    $horario_evento = $_POST['horario_evento'];    
    // Captura e remove espaços extras nas pontas
    $descricao = trim($_POST['descricao']);
    // SANITIZAÇÃO COMPLEMENTAR: Limite de Caracteres no Backend
    if (mb_strlen($descricao, 'UTF-8') > 500) {
        $mensagem = "<div class='msg-erro'>Erro de Validação: A descrição excede o limite máximo de 500 caracteres.</div>";
    }

    $mc_host = !empty($_POST['mc_host']) ? trim($_POST['mc_host']) : null;
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

    // =========================================================================
    // SANITIZAÇÃO COMPLEMENTAR: Regra de Negócio de Tipos de Evento
    // =========================================================================
    // Se o evento for Batalha de Rima (3) ou Slam (4), ignora qualquer estilo de dança injetado
    if ($id_tipo === 3 || $id_tipo === 4) {
        $estilos = []; 
    }
    // =========================================================================

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

    // NOVA LÓGICA DE PERSISTÊNCIA (INSERT vs UPDATE)
    if(empty($mensagem)) {
        try {
            $conn->beginTransaction();

            // Captura o ID do evento oculto no form (se existir, é um UPDATE)
            $id_evento_edit = !empty($_POST['id_evento_edit']) ? filter_var($_POST['id_evento_edit'], FILTER_VALIDATE_INT) : null;

            if ($id_evento_edit) {
                // ==========================================
                // FLUXO DE EDIÇÃO (UPDATE)
                // ==========================================
                
                // Validação de Segurança (IDOR): Garante que o evento pertence ao cara logado
                $stmtCheck = $conn->prepare("SELECT id_evento FROM evento WHERE id_evento = :id_evento AND id_usuario = :id_usuario");
                $stmtCheck->execute([':id_evento' => $id_evento_edit, ':id_usuario' => $_SESSION['id_usuario']]);
                if (!$stmtCheck->fetch()) {
                    throw new Exception("Evento não encontrado ou acesso não autorizado.");
                }

                // Constrói a query de Update
                $sqlEvento = "UPDATE evento SET 
                                id_tipo = :id_tipo, 
                                nome_evento = :nome_evento, 
                                data_evento = :data_evento, 
                                horario_evento = :horario_evento, 
                                descricao = :descricao, 
                                mc_host = :mc_host, 
                                dj = :dj, 
                                cep = :cep, 
                                estado = :estado, 
                                cidade = :cidade, 
                                bairro = :bairro, 
                                rua = :rua, 
                                numero = :numero, 
                                complemento = :complemento";

                // Se uma nova imagem foi enviada, adicionamos ao UPDATE. Se não, preservamos a existente.
                if ($caminho_imagem) {
                    $sqlEvento .= ", imagem_evento = :imagem_evento";
                }

                $sqlEvento .= " WHERE id_evento = :id_evento AND id_usuario = :id_usuario";
                
                $stmt = $conn->prepare($sqlEvento);
                
                // Bind dos parâmetros padrões
                $stmt->bindValue(':id_tipo', $id_tipo, PDO::PARAM_INT);
                $stmt->bindValue(':nome_evento', $nome_evento);
                $stmt->bindValue(':data_evento', $data_evento);
                $stmt->bindValue(':horario_evento', $horario_evento);
                $stmt->bindValue(':descricao', $descricao);
                $stmt->bindValue(':mc_host', $mc_host);
                $stmt->bindValue(':dj', $dj);
                $stmt->bindValue(':cep', $cep);
                $stmt->bindValue(':estado', $estado);
                $stmt->bindValue(':cidade', $cidade);
                $stmt->bindValue(':bairro', $bairro);
                $stmt->bindValue(':rua', $rua);
                $stmt->bindValue(':numero', $numero);
                $stmt->bindValue(':complemento', $complemento);
                $stmt->bindValue(':id_evento', $id_evento_edit, PDO::PARAM_INT);
                $stmt->bindValue(':id_usuario', $_SESSION['id_usuario'], PDO::PARAM_INT);
                
                // Bind condicional da imagem
                if ($caminho_imagem) {
                    $stmt->bindValue(':imagem_evento', $caminho_imagem);
                }

                $stmt->execute();

                // Gestão Pivot: Limpa os estilos antigos antes de salvar os novos
                $stmtDelEstilos = $conn->prepare("DELETE FROM ligacao_evento_estilo WHERE id_evento = :id_evento");
                $stmtDelEstilos->execute([':id_evento' => $id_evento_edit]);

                // Re-insere os estilos de dança atualizados
                if (!empty($estilos)) {
                    $sqlEstilo = "INSERT INTO ligacao_evento_estilo (id_evento, id_estilo_danca) VALUES (:id_evento, :id_estilo)";
                    $stmtEstilo = $conn->prepare($sqlEstilo);
                    foreach ($estilos as $id_estilo) {
                        $stmtEstilo->execute([
                            ':id_evento' => $id_evento_edit,
                            ':id_estilo' => $id_estilo
                        ]);
                    }
                }

                $mensagem = "<div class='msg-sucesso'>Alterações do evento salvas com sucesso!</div>";

            } else {
                // ==========================================
                // FLUXO DE CRIAÇÃO (INSERT - SEU CÓDIGO ORIGINAL)
                // ==========================================
                
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

                $mensagem = "<div class='msg-sucesso'>Evento publicado com sucesso na cena!</div>";
            }

            $conn->commit();

        } catch (Exception $e) {
            $conn->rollBack();
            $mensagem = "<div class='msg-erro'>Erro ao salvar o evento: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}

$tipos_evento = $conn->query("SELECT id_tipo, nome_tipo FROM tipo_evento ORDER BY nome_tipo")->fetchAll(PDO::FETCH_ASSOC);
$estilos_danca = $conn->query("SELECT id_estilo_danca, nome_estilo FROM estilo_danca ORDER BY nome_estilo")->fetchAll(PDO::FETCH_ASSOC);

require_once '../views/cadastro-evento.php';
?>