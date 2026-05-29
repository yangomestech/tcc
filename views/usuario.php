<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

// 1. Inclua a conexão (Certifique-se de que o caminho está correto!)
require_once '../config/.conexao.php';

// 2. BUSQUE OS DADOS DO BANCO PARA POPULAR A VARIÁVEL $userData
try {
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE id_usuario = :id");
    $stmt->execute(['id' => $_SESSION['id_usuario']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $userData = []; // Caso dê erro, deixamos vazio
}

// Se não achou usuário no banco, desloga
if (!$userData) {
    session_destroy();
    header("Location: ../views/login.php");
    exit();
}

// Define variáveis para uso rápido no header/iniciais
$username = $userData['username'] ?? 'Usuário';
$email = $userData['email_usuario'] ?? '';

// Lógica de iniciais
$words = explode(" ", trim($username));
$initials = (count($words) >= 2) 
    ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) 
    : strtoupper(substr($words[0], 0, 2));

$mensagem = $_SESSION['mensagem'] ?? "";
unset($_SESSION['mensagem']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Meu Perfil - BeatStreet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styleDashboard.css">
  <link rel="stylesheet" href="../assets/css/cadastro-evento.css">
</head>
<body>

<header class="header-sympla">
  <div class="logo">
  <a href="../controllers/dashboard-process.php">
    BEA<span class="roxo">T</span>S<span class="laranja">T</span>REET
</a>
  </div>
  
  <form class="search-sympla" action="busca.php" method="GET">
    <div class="search-box">
      <svg class="search-icon" viewBox="0 0 24 24"><path d="M10 2a8 8 0 016.32 12.9l4.387 4.387a1 1 0 01-1.414 1.415l-4.387-4.387A8 8 0 1110 2zm0 2a6 6 0 100 12 6 6 0 000-12z" fill="currentColor"/></svg>
      <input type="text" name="evento" placeholder="Buscar eventos, artistas...">
    </div>

    <div class="location-box">
      <svg class="location-icon" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z" fill="currentColor"/></svg>
      <span id="locationSelectedText">Qualquer lugar</span>
      <input type="hidden" name="cidade" id="cidadeInput" value="">
      <svg class="chevron-icon" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z" fill="currentColor"/></svg>

      <ul class="location-menu" id="locationMenu">
        <li class="location-item use-location" data-value="gps">
          <svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3c-.46-4.17-3.77-7.48-7.94-7.94V1h-2v2.06C6.83 3.52 3.52 6.83 3.06 11H1v2h2.06c.46 4.17 3.77 7.48 7.94 7.94V23h2v-2.06c4.17-.46 7.48-3.77 7.94-7.94H23v-2h-2.06zM12 19c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z" fill="currentColor"/></svg>
          Usar minha localização atual
        </li>
        <li class="location-item" data-value="">Qualquer lugar</li>
        <li class="location-item" data-value="SP">São Paulo (SP)</li>
        <li class="location-item" data-value="SC">Santa Catarina (SC)</li>
        <li class="location-item" data-value="RJ">Rio de Janeiro (RJ)</li>
        <li class="location-item" data-value="PR">Paraná (PR)</li>
        <li class="location-item" data-value="MG">Minas Gerais (MG)</li>
      </ul>
    </div>
    <button type="submit" style="display: none;">Buscar</button>
  </form> 

  <nav class="nav-links nav-desktop">
    <a href="../controllers/evento-process.php" class="nav-item">
      <svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor"/></svg>
      Criar evento
    </a>
    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24" width="20" height="20"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z" fill="currentColor"/></svg>
      Meus eventos
    </a>
    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24" width="20" height="20"><path d="M22 10V6c0-1.11-.9-2-2-2H4c-1.1 0-1.99.89-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2 7.74c-1.75-.8-3-2.58-3-4.74s1.25-3.94 3-4.74V6H4v2.26c1.75.8 3 2.58 3 4.74s-1.25 3.94-3 4.74V18h16v-.26z" fill="currentColor"/></svg>
      Meus ingressos
    </a>

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
          <li><a href="usuario.php"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/></svg> Minha conta</a></li>
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
    <h2>Minha Conta</h2>
    <p class="subtitle">Gerencie seus dados pessoais e preferências na BeatStreet.</p>

    <?= $mensagem ?>

<form id="profileForm" class="profile-form" action="../controllers/atualizaUser.php" method="POST">
        
      <h3 class="section-title">Informações Básicas</h3>

      <div class="form-group">
        <label for="username">Username: *</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" required placeholder="Seu nome de usuário">
      </div>

      <div class="form-group">
        <label for="nome_completo">Nome Completo: *</label>
        <input type="text" id="nome_completo" name="nome_completo" value="<?= htmlspecialchars($userData['nome_usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required placeholder="Seu nome completo">
      </div>

      <div class="form-group">
        <label for="email">E-mail: *</label>
        <input type="text" id="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required placeholder="seu.email@exemplo.com">
      </div>

      <div class="form-group row-flex">
        <div class="flex-1">
          <label for="cpf">CPF:</label>
          <input type="text" id="cpf" name="cpf" minlength="11" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="000.000.000-00" value="<?= htmlspecialchars($userData['cpf'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        
        <div class="flex-1">
          <label for="rg">RG:</label>
          <input type="text" id="rg" name="rg" minlength="9" maxlength="9" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="00.000.000-0" value="<?= htmlspecialchars($userData['rg'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"required>
        </div>
      </div>

      <div class="form-group row-flex">
        <div class="flex-1">
          <label for="telefone">Telefone:</label>
          <input type="text" id="telefone" oninput="this.value = this.value.replace(/[^0-9]/g, '');" minlength="11" maxlength="11" name="telefone" placeholder="(00) 00000-0000" value="<?= htmlspecialchars($userData['telefone_usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"required>
        </div>

        <div class="flex-1">
          <label for="cep">CEP:</label>
          <input type="text" id="cep" name="cep" minlength="8" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="00000-000" value="<?= htmlspecialchars($userData['cep'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"required>
        </div>
      </div>

      <h3 class="section-title">Endereço</h3>

      <div class="form-group row-flex">
        <div class="flex-2">
          <label for="rua">Rua:</label>
          <input type="text" id="rua" name="rua" placeholder="Nome da rua/avenida" value="<?= htmlspecialchars($userData['rua'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        
        <div class="flex-1">
          <label for="numero">Número:</label>
          <input type="text" id="numero" oninput="this.value = this.value.replace(/[^0-9]/g, '');"  maxlength="5" name="numero" placeholder="Ex: 123" value="<?= htmlspecialchars($userData['numero'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
      </div>

      <div class="form-group row-flex">
        <div class="flex-1">
          <label for="complemento">Complemento:</label>
          <input type="text" id="complemento" name="complemento" placeholder="Apt, Bloco, etc. (Opcional)" value="<?= htmlspecialchars($userData['complemento'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        
        <div class="flex-1">
          <label for="bairro">Bairro:</label>
          <input type="text" id="bairro" name="bairro" placeholder="Seu bairro" value="<?= htmlspecialchars($userData['bairro'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
      </div>

      <div class="form-group row-flex">
        <div class="flex-2">
          <label for="cidade">Cidade:</label>
          <input type="text" id="cidade" name="cidade" placeholder="Ex: São Paulo" value="<?= htmlspecialchars($userData['cidade'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        
        <div class="flex-1">
          <label for="estado">Estado:</label>
          <input type="text" id="estado" minlength="2" maxlength="2" oninput="this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');" name="estado" placeholder="Ex: SP" value="<?= htmlspecialchars($userData['estado'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"required>
        </div>
      </div>

      <h3 class="section-title">Biografia</h3>
      <div class="form-group">
        <label for="bio">Fale um pouco sobre você: (Opcional)</label>
        <textarea id="bio" name="bio" class="form-control" rows="5" placeholder="Sua relação com a cultura Hip Hop, estilos que dança, etc...">
          <?= htmlspecialchars($userData['descricao'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
        </textarea>
      </div>

      <button type="submit" class="btn-submit">Salvar Alterações</button>

    </form>
  </div>


</main>

<footer>
  <p>© 2026 BeatStreet - Todos os direitos reservados</p>
</footer>

<script src="../assets/js/menu.js"></script>
<script src="../assets/js/usuario.js"></script>

</body>
</html>