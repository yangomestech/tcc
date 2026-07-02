<?php
// A variável $imagem e o array $ev chegam através do include no loop do controller

$data_br = date('d/m/Y', strtotime($ev['data_evento']));
$hora_br = substr($ev['horario_evento'], 0, 5);
?>

<div class="evento-card card">
    <div class="card-img-box">
        <img src="<?= htmlspecialchars($imagem) ?>"> 
    </div>
    
    <div class="card-content" style="display: flex; flex-direction: column; gap: 6px; padding: 16px;">
        <h3 class="card-title" style="font-size: 1.3rem; font-weight: 600; margin-bottom: 4px; color: #fff;">
            <?= htmlspecialchars($ev['nome_evento']) ?>
        </h3>
        
        <div class="card-info-row" style="display: flex; align-items: center; gap: 8px; font-size: 0.95rem; color: #eee;">
            <svg viewBox="0 0 24 24" width="16" height="16" style="color: var(--roxo); min-width: 16px;"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z" fill="currentColor"/></svg>
            <span><?= $data_br ?> às <?= $hora_br ?></span>
        </div>
        
        <div class="card-info-row" style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: #aaa;">
            <svg viewBox="0 0 24 24" width="14" height="14" style="color: var(--laranja); min-width: 14px;"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/></svg>
            <span><?= htmlspecialchars($ev['cidade']) . ' - ' . htmlspecialchars($ev['estado']) ?></span>
        </div>
        
        <a href="../controllers/detalhe-evento.php?id=<?= $ev['id_evento'] ?>" style="text-decoration: none; margin-top: auto;">
            <button class="btn-detalhes" style="width: 100%; margin-top: 12px; cursor: pointer;">Ver detalhes</button>
        </a>
    </div>
</div>