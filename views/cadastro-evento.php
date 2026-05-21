<?php
session_start();

// PROTEÇÃO DE ROTA E CONTROLE DE ACESSO
if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado. Apenas usuários logados podem criar eventos.");
}

require_once '../config/conexao.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Coleta dos dados do formulário
    $id_tipo        = $_POST['id_tipo'];
    $nome_evento    = $_POST['nome_evento'];
    $data_evento    = $_POST['data_evento'];
    $horario_evento = $_POST['horario_evento'];
    
    $mc_host = !empty($_POST['mc_host']) ? $_POST['mc_host'] : null;
    $dj      = !empty($_POST['dj']) ? $_POST['dj'] : null;
    
    // Endereço
    $cep    = $_POST['cep'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $rua    = $_POST['rua'];
    $numero = !empty($_POST['numero']) ? $_POST['numero'] : null;
    $complemento = !empty($_POST['complemento']) ? $_POST['complemento'] : null;

    // Array de estilos selecionados (Checkboxes)
    $estilos = isset($_POST['estilos']) ? $_POST['estilos'] : [];

    try {
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

        // O PHP já está preparado: se $estilos for vazio (porque o bloco estava oculto), ele simplesmente ignora essa etapa!
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
        echo "<div style='color: green; padding: 10px; border: 1px solid green; margin-bottom: 20px;'>Evento cadastrado com sucesso! A cena agradece.</div>";

    } catch (Exception $e) {
        $conn->rollBack();
        echo "<div style='color: red; padding: 10px; border: 1px solid red; margin-bottom: 20px;'>Erro ao cadastrar evento. Tente novamente.</div>";
    }
}

// BUSCAS PARA PREENCHER O FORMULÁRIO DINAMICAMENTE
$tipos_evento = $conn->query("SELECT id_tipo, nome_tipo FROM tipo_evento ORDER BY nome_tipo")->fetchAll(PDO::FETCH_ASSOC);
$estilos_danca = $conn->query("SELECT id_estilo_danca, nome_estilo FROM estilo_danca ORDER BY nome_estilo")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Evento - Cultura Hip Hop</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input[type="text"], input[type="time"], input[type="date"], select { width: 100%; padding: 8px; box-sizing: border-box; }
        .checkbox-group label { display: inline-block; font-weight: normal; margin-right: 15px; margin-bottom: 5px; }
        .btn-submit { background-color: #000; color: #fff; padding: 10px 20px; border: none; cursor: pointer; font-size: 16px; margin-top: 20px;}
        .btn-submit:hover { background-color: #333; }
        hr { margin: 30px 0; border: 1px solid #ccc; }
        
        /* Adicionamos uma transição suave para não "piscar" na tela de forma abrupta */
        #bloco_estilos_danca { display: none; transition: all 0.3s ease; }
    </style>
</head>
<body>

    <h2>Cadastrar Novo Evento na Cena</h2>
    <p>Preencha os dados abaixo para divulgar sua Batalha, Jam ou Slam.</p>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nome_evento">Nome do Evento: *</label>
            <input type="text" id="nome_evento" name="nome_evento" required>
        </div>

        <div class="form-group">
            <label for="id_tipo">Tipo de Evento: *</label>
            <select id="id_tipo" name="id_tipo" required>
                <option value="">Selecione...</option>
                <?php foreach ($tipos_evento as $tipo): ?>
                    <option value="<?= $tipo['id_tipo'] ?>"><?= htmlspecialchars($tipo['nome_tipo']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label for="data_evento">Data: *</label>
                <input type="date" id="data_evento" name="data_evento" required>
            </div>
            <div style="flex: 1;">
                <label for="horario_evento">Horário de Início: *</label>
                <input type="time" id="horario_evento" name="horario_evento" required>
            </div>
        </div>

        <hr>
        <h3>Elementos da Cultura</h3>

        <div class="form-group">
            <label for="mc_host">Mestre de Cerimônia (MC / Host):</label>
            <input type="text" id="mc_host" name="mc_host" placeholder="Quem vai conduzir?">
        </div>

        <div class="form-group">
            <label for="dj">DJ (Residente ou Convidado):</label>
            <input type="text" id="dj" name="dj" placeholder="Quem vai soltar os beats?">
        </div>

        <div class="form-group checkbox-group" id="bloco_estilos_danca">
            <label>Estilos de Dança presentes no evento:</label><br>
            <?php foreach ($estilos_danca as $estilo): ?>
                <label>
                    <input type="checkbox" name="estilos[]" class="checkbox-estilo" value="<?= $estilo['id_estilo_danca'] ?>">
                    <?= htmlspecialchars($estilo['nome_estilo']) ?>
                </label>
            <?php endforeach; ?>
        </div>

        <hr>
        <h3>Localização</h3>

        <div class="form-group" style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" maxlength="9" placeholder="00000-000">
            </div>
            <div style="flex: 1;">
                <label for="estado">Estado (UF):</label>
                <input type="text" id="estado" name="estado" maxlength="2" placeholder="SP">
            </div>
            <div style="flex: 2;">
                <label for="cidade">Cidade: *</label>
                <input type="text" id="cidade" name="cidade" required>
            </div>
        </div>

        <div class="form-group" style="display: flex; gap: 20px;">
            <div style="flex: 2;">
                <label for="rua">Rua / Logradouro: *</label>
                <input type="text" id="rua" name="rua" required>
            </div>
            <div style="flex: 1;">
                <label for="numero">Número:</label>
                <input type="text" id="numero" name="numero" placeholder="Ex: S/N">
            </div>
        </div>

        <div class="form-group" style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label for="bairro">Bairro: *</label>
                <input type="text" id="bairro" name="bairro" required>
            </div>
            <div style="flex: 1;">
                <label for="complemento">Complemento (Referência):</label>
                <input type="text" id="complemento" name="complemento" placeholder="Praça, Pista de Skate...">
            </div>
        </div>

        <button type="submit" class="btn-submit">Criar Evento</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectTipoEvento = document.getElementById('id_tipo');
            const blocoEstilosDanca = document.getElementById('bloco_estilos_danca');
            const checkboxesEstilos = document.querySelectorAll('.checkbox-estilo');

            selectTipoEvento.addEventListener('change', function() {
                // Pegamos o texto da opção que o usuário escolheu no dropdown e deixamos tudo minúsculo para facilitar a busca
                const textoSelecionado = this.options[this.selectedIndex].text.toLowerCase();

                // Verificamos se o nome do tipo do evento tem a palavra "dança" ou "jam"
                if (textoSelecionado.includes('dança') || textoSelecionado.includes('jam')) {
                    blocoEstilosDanca.style.display = 'block'; // Mostra o bloco
                } else {
                    blocoEstilosDanca.style.display = 'none'; // Esconde o bloco
                    
                    // Limpeza de segurança: Se o usuário marcou estilos e depois trocou para "Slam", nós desmarcamos os checkboxes pra não ir sujeira pro banco
                    checkboxesEstilos.forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                }
            });
        });
    </script>
</body>
</html>