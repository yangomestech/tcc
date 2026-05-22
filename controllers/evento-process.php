<?php
session_start();

// 1. PROTEÇÃO DE ROTA E CONTROLE DE ACESSO
if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado. Apenas usuários logados podem criar eventos.");
}

// 2. CONEXÃO COM O BANCO DE DADOS
require_once '../config/conexao.php'; 

$mensagem = ''; // Variável para armazenar mensagens de sucesso ou erro para a View

// 3. PROCESSAMENTO DO FORMULÁRIO (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Coleta dos dados
    $id_tipo        = $_POST['id_tipo'];
    $nome_evento    = $_POST['nome_evento'];
    $data_evento    = $_POST['data_evento'];
    $horario_evento = $_POST['horario_evento'];
    $mc_host        = !empty($_POST['mc_host']) ? $_POST['mc_host'] : null;
    $dj             = !empty($_POST['dj']) ? $_POST['dj'] : null;
    
    // Endereço
    $cep         = $_POST['cep'];
    $estado      = $_POST['estado'];
    $cidade      = $_POST['cidade'];
    $bairro      = $_POST['bairro'];
    $rua         = $_POST['rua'];
    $numero      = !empty($_POST['numero']) ? $_POST['numero'] : null;
    $complemento = !empty($_POST['complemento']) ? $_POST['complemento'] : null;

    // Array de estilos selecionados
    $estilos = isset($_POST['estilos']) ? $_POST['estilos'] : [];

    try {
        // Transação PDO: Tudo ou nada
        $conn->beginTransaction();

        $sqlEvento = "INSERT INTO evento 
            (id_usuario, id_tipo, nome_evento, data_evento, horario_evento, mc_host, dj, cep, estado, cidade, bairro, rua, numero, complemento) 
            VALUES 
            (:id_usuario, :id_tipo, :nome_evento, :data_evento, :horario_evento, :mc_host, :dj, :cep, :estado, :cidade, :bairro, :rua, :numero, :complemento)";
        
        $stmt = $conn->prepare($sqlEvento);
        $stmt->execute([
            ':id_usuario'    => $_SESSION['id_usuario'],
            ':id_tipo'       => $id_tipo,
            ':nome_evento'   => $nome_evento,
            ':data_evento'   => $data_evento,
            ':horario_evento'=> $horario_evento,
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

        // Inserção dos estilos de dança (se houver)
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
        $mensagem = "<div class='msg-sucesso'>Evento cadastrado com sucesso! A cena agradece.</div>";

    } catch (Exception $e) {
        $conn->rollBack();
        $mensagem = "<div class='msg-erro'>Erro ao cadastrar evento. Tente novamente.</div>";
    }
}

// 4. BUSCAS PARA PREENCHER O FORMULÁRIO DINAMICAMENTE
// Essas variáveis serão utilizadas lá na View
$tipos_evento = $conn->query("SELECT id_tipo, nome_tipo FROM tipo_evento ORDER BY nome_tipo")->fetchAll(PDO::FETCH_ASSOC);
$estilos_danca = $conn->query("SELECT id_estilo_danca, nome_estilo FROM estilo_danca ORDER BY nome_estilo")->fetchAll(PDO::FETCH_ASSOC);

// 5. CARREGAMENTO DA VIEW
// Depois de toda a lógica feita, chamamos a interface visual
require_once '../views/cadastro-evento.php';
?>