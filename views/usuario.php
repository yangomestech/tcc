<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

// 1. CONEXÃO COM O BANCO DE DADOS AQUI!
// Precisamos conectar para buscar os dados completos do usuário
require_once __DIR__ . '/../config/.conexao.php'; 

$id_usuario = $_SESSION['id_usuario'];

// 2. BUSCANDO OS DADOS ATUALIZADOS DIRETO DO BANCO
try {
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE id_usuario = :id");
    $stmt->execute([':id' => $id_usuario]);
    $userDB = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$userDB) {
        $userDB = []; // Fallback por segurança
    }
} catch (PDOException $e) {
    $userDB = [];
}

// 3. ATRIBUINDO OS VALORES DO BANCO ÀS VARIÁVEIS DO FORMULÁRIO
$username         = $userDB['username'] ?? 'Usuário';
$nome_usuario     = $userDB['nome_usuario'] ?? ''; // No banco é nome_usuario, no form é nome_completo
$email            = $userDB['email_usuario'] ?? '';
$rg               = $userDB['rg'] ?? '';
$cpf              = $userDB['cpf'] ?? '';
$telefone_usuario = $userDB['telefone_usuario'] ?? '';
$cep              = $userDB['cep'] ?? '';
$rua              = $userDB['rua'] ?? '';
$numero           = $userDB['numero'] ?? '';
$complemento      = $userDB['complemento'] ?? '';
$bairro           = $userDB['bairro'] ?? '';
$cidade           = $userDB['cidade'] ?? '';
$estado           = $userDB['estado'] ?? '';
// Se você for usar a bio no futuro, a variável é essa: $descricao = $userDB['descricao'] ?? '';

// Lógica para pegar as iniciais do usuário
$words = explode(" ", trim($username));
$initials = "";
if (count($words) >= 2) {
    $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
} else {
    $initials = strtoupper(substr($words[0], 0, 2));
}

// Captura a mensagem de sucesso/erro vinda do banco e limpa da sessão
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
          <li><a href="../controllers/favoritos-process.php"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/></svg> Favoritos</a></li>
          <li><a href="../controllers/evento-process.php"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor"/></svg> Criar evento</a></li>
          <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z" fill="currentColor"/></svg> Meus eventos</a></li>
          <li class="divider"></li>
          <li><a href="#"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z" fill="currentColor"/></svg> Suporte</a></li>
          <li class="divider"></li>
          <li><a href="../controllers/logout-process.php" class="logout-link"><svg viewBox="0 0 24 24" width="20" height="20"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" fill="currentColor"/></svg> Sair</a></li>
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

<?php if (isset($_SESSION['erro_documentos'])): ?>
    <div style="background-color: #ff4d4d; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; text-align: center;">
        <?= htmlspecialchars($_SESSION['erro_documentos']); ?>
    </div>
    <?php unset($_SESSION['erro_documentos']); ?>
<?php endif; ?>
        
      <h3 class="section-title">Informações Básicas</h3>

      <div class="form-group">
        <label for="username">Username: *</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" required placeholder="Seu nome de usuário">
      </div>

      <div class="form-group">
        <label for="nome_completo">Nome Completo: *</label>
        <input type="text" id="nome_completo" name="nome_completo" required placeholder="Seu nome completo" value="<?= htmlspecialchars($nome_usuario, ENT_QUOTES, 'UTF-8'); ?>">
      </div>

      <div class="form-group">
        <label for="email">E-mail: *</label>
        <input type="text" id="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required placeholder="seu.email@exemplo.com">
      </div>

      <div class="form-group row-flex">
        <div class="flex-1">
          <label for="cpf">CPF:</label>
          <input type="text" id="cpf" name="cpf" maxlength="11" oninput="this.value = this.value.replace(/\D/g, '')" placeholder="000.000.000-00"  value="<?= htmlspecialchars($cpf, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        
        <div class="flex-1">
          <label for="rg">RG:</label>
          <input type="text" id="rg" name="rg" maxlength="10" placeholder="00.000.000-0" oninput="this.value = this.value.replace(/\D/g, '')" value="<?= htmlspecialchars($rg, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
      </div>

      <div class="form-group row-flex">
        <div class="flex-1">
          <label for="telefone">Telefone:</label>
          <input type="text" id="telefone" maxlength="11" name="telefone" oninput="this.value = this.value.replace(/\D/g, '')" placeholder="(00) 00000-0000" value="<?= htmlspecialchars($telefone_usuario, ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div class="flex-1">
          <label for="cep">CEP:</label>
          <input type="text" id="cep" name="cep" maxlength="8" oninput="this.value = this.value.replace(/\D/g, '')" placeholder="00000-000" value="<?= htmlspecialchars($cep, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
      </div>

      <h3 class="section-title">Endereço</h3>

      <div class="form-group row-flex">
        <div class="flex-2">
          <label for="rua">Rua:</label>
          <input type="text" id="rua" name="rua" placeholder="Nome da rua/avenida" value="<?= htmlspecialchars($rua, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        
        <div class="flex-1">
          <label for="numero">Número:</label>
          <input type="text" id="numero" name="numero" maxlength="5" placeholder="Ex: 123" value="<?= htmlspecialchars($numero, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
      </div>

      <div class="form-group row-flex">
        <div class="flex-1">
          <label for="complemento">Complemento:</label>
          <input type="text" id="complemento" name="complemento" placeholder="Apt, Bloco, etc. (Opcional)" value="<?= htmlspecialchars($complemento, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        
        <div class="flex-1">
          <label for="bairro">Bairro:</label>
          <input type="text" id="bairro" name="bairro" placeholder="Seu bairro" value="<?= htmlspecialchars($bairro, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
      </div>

      <div class="form-group row-flex">
        <div class="flex-2">
          <label for="cidade">Cidade:</label>
          <input type="text" id="cidade" name="cidade" oninput="this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '')" placeholder="Ex: São Paulo" value="<?= htmlspecialchars($cidade, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        
        <div class="flex-1">
          <label for="estado">Estado:</label>
          <input type="text" id="estado" name="estado" placeholder="Ex: SP" oninput="this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '')" maxlength="2" value="<?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
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