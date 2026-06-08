<?php
// Trata Imagem
$img_fallback = "../assets/img/computador1.jpg";
$imagem = !empty($ev['imagem_evento']) ? $ev['imagem_evento'] : $img_fallback;

// Formata Data
$data_br = date('d/m/Y', strtotime($ev['data_evento']));
$hora_br = substr($ev['horario_evento'], 0, 5);

// Trata Tags
$tags = [];
if (!empty($ev['estilos_danca'])) {
    $tags = array_filter(array_map('trim', explode(',', $ev['estilos_danca'])));
}
?>

<a href="detalhe-evento.php?id=<?= $ev['id_evento'] ?>" class="evento-card">
    <div class="card-img-box">
        <img src="<?= htmlspecialchars($imagem) ?>" alt="Cartaz">
        <span class="badge-tipo"><?= htmlspecialchars($ev['nome_tipo']) ?></span>
    </div>
    
    <div class="card-content">
        <h3 class="card-title"><?= htmlspecialchars($ev['nome_evento']) ?></h3>
        
        <div class="card-info-row">
            <svg viewBox="0 0 24 24" width="16" height="16"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z" fill="currentColor"/></svg>
            <span><?= $data_br ?> às <?= $hora_br ?></span>
        </div>
        
        <div class="card-info-row">
            <svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/></svg>
            <span><?= htmlspecialchars($ev['cidade']) . ' - ' . htmlspecialchars($ev['estado']) ?></span>
        </div>

        <div class="card-info-row highlight-org">
            <strong>@<?= htmlspecialchars($ev['organizador_arroba']) ?></strong>
            <span class="contador-presenca"><?= $ev['total_presencas'] ?> confirmados</span>
        </div>

        <?php if (!empty($tags)): ?>
            <div class="card-tags-mini">
                <?php foreach(array_slice($tags, 0, 3) as $tag): // Limita a 3 tags no card ?>
                    <span class="mini-tag"><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
                <?php if(count($tags) > 3): ?>
                    <span class="mini-tag">+<?= count($tags) - 3 ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</a>