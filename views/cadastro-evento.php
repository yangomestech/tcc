<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Evento - Cultura Hip Hop</title>
    <link rel="stylesheet" href="../assets/css/cadastro-evento.css">
</head>
<body>

    <h2>Cadastrar Novo Evento na Cena</h2>
    <p>Preencha os dados abaixo para divulgar sua Batalha, Jam ou Slam.</p>

    <?= $mensagem ?>

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

        <div class="form-group row-flex">
            <div class="flex-1">
                <label for="data_evento">Data: *</label>
                <input type="date" id="data_evento" name="data_evento" required>
            </div>
            <div class="flex-1">
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

        <div class="form-group row-flex">
            <div class="flex-1">
                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" maxlength="9" placeholder="00000-000">
            </div>
            <div class="flex-1">
                <label for="estado">Estado (UF):</label>
                <input type="text" id="estado" name="estado" maxlength="2" placeholder="SP">
            </div>
            <div class="flex-2">
                <label for="cidade">Cidade: *</label>
                <input type="text" id="cidade" name="cidade" required>
            </div>
        </div>

        <div class="form-group row-flex">
            <div class="flex-2">
                <label for="rua">Rua / Logradouro: *</label>
                <input type="text" id="rua" name="rua" required>
            </div>
            <div class="flex-1">
                <label for="numero">Número:</label>
                <input type="text" id="numero" name="numero" placeholder="Ex: S/N">
            </div>
        </div>

        <div class="form-group row-flex">
            <div class="flex-1">
                <label for="bairro">Bairro: *</label>
                <input type="text" id="bairro" name="bairro" required>
            </div>
            <div class="flex-1">
                <label for="complemento">Complemento (Referência):</label>
                <input type="text" id="complemento" name="complemento" placeholder="Praça, Pista de Skate...">
            </div>
        </div>

        <button type="submit" class="btn-submit">Criar Evento</button>
    </form>

    <script src="../assets/js/cadastroEvento.js"></script>
</body>
</html>