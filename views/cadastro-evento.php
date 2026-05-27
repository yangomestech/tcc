<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? 'Usuário';
$words = explode(" ", trim($username));
$initials = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($words[0], 0, 2));
$email = $_SESSION['email_usuario'] ?? 'usuario@beatstreet.com';

// Fallback de variáveis do controlador (para não quebrar a view se acessada diretamente)
$mensagem = $mensagem ?? "";
$tipos_evento = $tipos_evento ?? [];
$estilos_danca = $estilos_danca ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Evento - BeatStreet</title>
    <link rel="stylesheet" href="../assets/css/cadastro-evento.css">
</head>
<body>

    <header class="header-sympla">
        <a href="../controllers/dashboard-process.php" class="logo">
            BEA<span class="roxo">T</span>S<span class="laranja">T</span>REET
        </a>
        
<nav class="nav-links nav-desktop">
            <div class="user-menu-container">
                <button class="user-profile-btn" id="userMenuBtn">
                    <svg class="hamburger-icon" viewBox="0 0 24 24" width="24" height="24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" fill="currentColor"/></svg>
                    <div class="user-initials"><?= $initials; ?></div>
                </button>

                <div class="user-dropdown" id="userDropdown">
                    <div class="dropdown-header">
                        <div class="user-initials-large"><?= $initials; ?></div>
                        <div class="user-info">
                            <strong><?= htmlspecialchars(strtoupper($username), ENT_QUOTES, 'UTF-8'); ?></strong>
                            <span><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    </div>

                    <ul class="dropdown-list">
                        <li><a href="../views/usuario.php"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/></svg> Minha conta</a></li>
                        
                        <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/></svg> Favoritos</a></li>
                        
                        <li><a href="../controllers/evento-process.php"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor"/></svg> Criar evento</a></li>
                        
                        <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z" fill="currentColor"/></svg> Meus eventos</a></li>
                        
                        <li class="divider"></li>
                        
                        <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z" fill="currentColor"/></svg> Suporte</a></li>
                        
                        <li class="divider"></li>
                        
                        <li><a href="../index.php?action=logout" class="logout-link"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" fill="currentColor"/></svg> Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="page-wrapper">
        <div class="form-container">
            <h2>Cadastrar Novo Evento na Cena</h2>
            <p class="subtitle">Preencha os dados abaixo para divulgar sua Batalha, Jam ou Slam.</p>

            <?= $mensagem ?>

            <form method="POST" action="../controllers/evento-process.php" enctype="multipart/form-data">
                
                <h3 class="section-title"><span class="num">1</span> Informações Básicas</h3>
                
                <div class="form-group">
                    <label>Imagem de divulgação (Opcional)</label>
                    <div class="image-upload-box" id="image-preview-container">
                        <input type="file" id="imagem_evento" name="imagem_evento" accept="image/png, image/jpeg, image/webp">
                        <div class="upload-placeholder" id="upload-text">
                            <svg viewBox="0 0 24 24" width="48" height="48"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z" fill="currentColor"/></svg>
                            <span>Clique ou arraste a imagem do cartaz aqui</span>
                            <small style="color: #666; margin-top: 5px;">Formatos: JPEG, PNG. A imagem se ajustará automaticamente sem distorcer.</small>
                        </div>
                        <img id="preview-image" class="preview-image" src="" alt="Pré-visualização do Evento">
                    </div>
                </div>

                <div class="form-group">
                    <label for="nome_evento">Nome do Evento: *</label>
                    <input type="text" id="nome_evento" name="nome_evento" placeholder="Ex: Batalha da Aldeia" required>
                </div>

                <div class="form-group">
                    <label for="id_tipo">Classifique seu evento (Assunto): *</label>
                    <select id="id_tipo" name="id_tipo" required>
                        <option value="">Selecione um assunto...</option>
                        <?php foreach ($tipos_evento as $tipo): ?>
                            <option value="<?= $tipo['id_tipo'] ?>"><?= htmlspecialchars($tipo['nome_tipo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group checkbox-group" id="bloco_estilos_danca">
                    <label style="display: block; color: #fff; margin-bottom: 10px;">Quais estilos de dança estarão na roda? *</label>
                    <?php foreach ($estilos_danca as $estilo): ?>
                        <label>
                            <input type="checkbox" name="estilos[]" class="checkbox-estilo" value="<?= $estilo['id_estilo_danca'] ?>">
                            <?= htmlspecialchars($estilo['nome_estilo']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <h3 class="section-title"><span class="num">2</span> Data e Horário</h3>
                <div class="form-group row-flex">
                    <div class="flex-1">
                        <label for="data_evento">Data de Início: *</label>
                        <input type="date" id="data_evento" name="data_evento" required onclick="this.showPicker()">
                    </div>
                    <div class="flex-1">
                        <label for="horario_evento">Horário de Início: *</label>
                        <select id="horario_evento" name="horario_evento" required>
                            </select>
                    </div>
                </div>

                <h3 class="section-title"><span class="num">3</span> Descrição do Evento</h3>
                <div class="form-group">
                    <label for="descricao">Conte mais detalhes sobre o evento: *</label>
                    <textarea id="descricao" name="descricao" class="form-control" placeholder="Descreva as atrações, regras das batalhas, premiação ou como chegar no pico..." required></textarea>
                </div>

                <div class="form-group row-flex">
                    <div class="flex-1">
                        <label for="mc_host">Mestre de Cerimônia (Host):</label>
                        <input type="text" id="mc_host" name="mc_host" placeholder="Quem vai conduzir?">
                    </div>
                    <div class="flex-1">
                        <label for="dj">DJ (Residente ou Convidado):</label>
                        <input type="text" id="dj" name="dj" placeholder="Quem solta os beats?">
                    </div>
                </div>

                <h3 class="section-title"><span class="num">4</span> Onde o seu evento vai acontecer?</h3>
                <div class="form-group row-flex">
                    <div class="flex-1">
                        <label for="cep">CEP: *</label>
                        <input type="text" id="cep" name="cep" maxlength="9" placeholder="00000-000" required>
                    </div>
                    <div class="flex-1">
                        <label for="estado">Estado (UF): *</label>
                        <input type="text" id="estado" name="estado" maxlength="2" placeholder="SP" required>
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
                        <label for="numero">Número: *</label>
                        <input type="text" id="numero" name="numero" placeholder="Ex: S/N" required>
                    </div>
                </div>

                <div class="form-group row-flex">
                    <div class="flex-1">
                        <label for="bairro">Bairro: *</label>
                        <input type="text" id="bairro" name="bairro" required>
                    </div>
                    <div class="flex-1">
                        <label for="complemento">Complemento (Opcional):</label>
                        <input type="text" id="complemento" name="complemento" placeholder="Praça, Pista de Skate...">
                    </div>
                </div>

                <button type="submit" class="btn-submit">Publicar Evento</button>
            </form>
        </div>
    </main>

    <script src="../assets/js/menu.js"></script>
    <script src="../assets/js/cadastroEvento.js"></script>
</body>
</html>
