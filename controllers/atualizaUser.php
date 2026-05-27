<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Verificar se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

// 2. Incluir o seu arquivo de conexão com o banco de dados
// OBS: Ajuste o caminho abaixo se sua conexão estiver em outra pasta
require_once '../config/.conexao.php'; 

$id_usuario = $_SESSION['id_usuario'];

// 3. Verificar se a requisição veio via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Captura os dados e remove espaços extras 
    $username      = trim($_POST['username'] ?? '');
    $nome_usuario  = trim($_POST['nome_completo'] ?? ''); 
    $email_usuario = trim($_POST['email'] ?? '');         
    $cpf           = trim($_POST['cpf'] ?? null);
    $rg            = trim($_POST['rg'] ?? null);
    $telefone      = trim($_POST['telefone'] ?? null);
    $cep           = trim($_POST['cep'] ?? null);
    $rua           = trim($_POST['rua'] ?? null);
    $numero        = trim($_POST['numero'] ?? null);
    $complemento   = trim($_POST['complemento'] ?? null);
    $bairro        = trim($_POST['bairro'] ?? null);
    $cidade        = trim($_POST['cidade'] ?? null);
    $estado        = trim($_POST['estado'] ?? null);
    $descricao     = trim($_POST['bio'] ?? null);         

    // Trata strings vazias como NULL para respeitar a estrutura das chaves UNIQUE do seu banco
    $cpf         = $cpf === '' ? null : $cpf;
    $rg          = $rg === '' ? null : $rg;
    $telefone    = $telefone === '' ? null : $telefone;
    $cep         = $cep === '' ? null : $cep;
    $rua         = $rua === '' ? null : $rua;
    $numero      = $numero === '' ? null : $numero;
    $complemento = $complemento === '' ? null : $complemento;
    $bairro      = $bairro === '' ? null : $bairro;
    $cidade      = $cidade === '' ? null : $cidade;
    $estado      = $estado === '' ? null : $estado;
    $descricao   = $descricao === '' ? null : $descricao;

    // Validação de campos obrigatórios do banco
    if (empty($username) || empty($nome_usuario) || empty($email_usuario)) {
        $_SESSION['mensagem'] = "<div class='alert alert-danger' style='color: #ef4444; margin-bottom: 20px;'>Por favor, preencha todos os campos obrigatórios (*).</div>";
        header("Location: usuario.php");
        exit();
    }

    try {
        // 4. Query SQL baseada nas suas colunas exatas do MariaDB
        $sql = "UPDATE usuario SET 
                    username = :username, 
                    nome_usuario = :nome_usuario, 
                    email_usuario = :email_usuario, 
                    cpf = :cpf, 
                    rg = :rg, 
                    telefone_usuario = :telefone, 
                    cep = :cep, 
                    rua = :rua, 
                    numero = :numero, 
                    complemento = :complemento, 
                    bairro = :bairro, 
                    cidade = :cidade, 
                    estado = :estado, 
                    descricao = :descricao
                WHERE id_usuario = :id_usuario";

        $stmt = $conn->prepare($sql);

        // 5. Vinculação limpa protegida de SQL Injection
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':nome_usuario', $nome_usuario);
        $stmt->bindParam(':email_usuario', $email_usuario);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':rg', $rg);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':rua', $rua);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':complemento', $complemento);
        $stmt->bindParam(':bairro', $bairro);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

        // 6. Executa a gravação
        if ($stmt->execute()) {
            // Atualiza os dados locais de sessão ativos
            $_SESSION['username'] = $username;
            $_SESSION['email_usuario'] = $email_usuario;

            $_SESSION['mensagem'] = "<div class='alert alert-success' style='color: #4ade80; margin-bottom: 20px;'>Dados atualizados com sucesso!</div>";
        } else {
            $_SESSION['mensagem'] = "<div class='alert alert-danger' style='color: #ef4444; margin-bottom: 20px;'>Erro ao atualizar os dados. Tente novamente.</div>";
        }

    } catch (PDOException $e) {
        // Captura violações de restrição UNIQUE (Erros de duplicidade no banco)
        if ($e->getCode() == 23000) {
            $_SESSION['mensagem'] = "<div class='alert alert-danger' style='color: #ef4444; margin-bottom: 20px;'>Erro: O Username, E-mail, CPF, RG ou Telefone digitado já está em uso por outro usuário.</div>";
        } else {
            $_SESSION['mensagem'] = "<div class='alert alert-danger' style='color: #ef4444; margin-bottom: 20px;'>Erro no banco de dados: " . $e->getMessage() . "</div>";
        }
    }

    header("Location:../views/usuario.php");
    exit();
} else {
    header("Location:../views/usuario.php");
    exit();
}