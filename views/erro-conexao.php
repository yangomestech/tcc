<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BeatStreet | Fora do ar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/styleErroConexao.css">
</head>

<body>

    <header class="error-header">
        <a href="../controllers/dashboard-process.php" class="logo">
            BEA<span class="roxo">T</span><span class="laranja">S</span>TREET
        </a>

        <div class="header-status">
            <span class="status-dot"></span>
            Conexão temporariamente indisponível
        </div>
    </header>

    <main class="error-page">
        <section class="error-card">

            <div class="error-badge">
                Erro de conexão
            </div>

            <div class="error-icon">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 17.5C5.8 15.9 8.6 15 12 15C15.4 15 18.2 15.9 20 17.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M7 13C8.2 12 9.9 11.5 12 11.5C14.1 11.5 15.8 12 17 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M10 8.5C10.6 8.2 11.3 8 12 8C12.7 8 13.4 8.2 14 8.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 20H12.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>

            <h1>
                A cena ficou <span class="gradient-text">fora do ar</span>
            </h1>

            <p>
                Não conseguimos conectar com o backstage do BeatStreet agora.
                Pode ser uma instabilidade temporária no banco de dados.
                Tente novamente em alguns instantes.
            </p>

            <div class="error-actions">
                <button class="btn btn-primary" onclick="window.location.reload()">
                    Tentar novamente
                </button>

                <a href="../controllers/dashboard-process.php" class="btn btn-secondary">
                    Voltar para o início
                </a>
            </div>

            <div class="support-text">
                Código: DB_CONNECTION_FAILED
            </div>

            <?php if (isset($isDev) && $isDev): ?>
                <details class="dev-details">
                    <summary>Detalhes técnicos — visível apenas em desenvolvimento</summary>
                    <code><?php echo $erroSeguro ?? 'Erro desconhecido.'; ?></code>
                </details>
            <?php endif; ?>

        </section>
    </main>

</body>
</html>