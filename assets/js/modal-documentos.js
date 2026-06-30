function abrirModalDocumentos(event) {
    // Evita que o link "pule" para o topo da página ao clicar no "#"
    if(event) {
        event.preventDefault(); 
    }

    // Se o modal já estiver aberto, não faz nada
    if (document.getElementById("modalDocs")) return;

    // Estrutura do Modal HTML
    const modalHTML = `
        <div id="modalDocs" class="modal-docs-overlay">
            <div class="modal-docs-content">
                <h2>Aviso de Segurança</h2>
                <p>Identificamos que seu perfil ainda não possui RG e CPF cadastrados. Para criar e publicar eventos na plataforma, você deseja fornecer esses documentos agora?</p>
                <div class="modal-docs-actions">
                    <button id="btnNaoInteresse" class="btn-modal btn-secondary-modal">Não tenho Interesse</button>
                    <button id="btnFornecerDocs" class="btn-modal btn-primary-modal">Fornecer Documentos</button>
                </div>
            </div>
        </div>
    `;

    // Insere o modal no corpo da página
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // Funcionalidade: Fechar ao clicar fora (no overlay)
    const modalOverlay = document.getElementById("modalDocs");
    modalOverlay.addEventListener("click", function(e) {
        if (e.target === modalOverlay) {
            modalOverlay.remove();
        }
    });

    // Funcionalidade: Fechar ao pressionar a tecla ESC
    const fecharComEsc = function(e) {
        if (e.key === "Escape") {
            const modal = document.getElementById("modalDocs");
            if (modal) {
                modal.remove();
                document.removeEventListener("keydown", fecharComEsc);
            }
        }
    };
    document.addEventListener("keydown", fecharComEsc);

    // Injeta os estilos do modal (se ainda não existirem)
    if (!document.getElementById("modalDocsStyles")) {
        const estilos = `
            <style id="modalDocsStyles">
                .modal-docs-overlay {
                    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                    background: rgba(0, 0, 0, 0.8); display: flex;
                    justify-content: center; align-items: center; z-index: 99999;
                    animation: fadeInDocs 0.3s ease;
                }
                .modal-docs-content {
                    background: #1a1a1a; color: #fff; padding: 30px; border-radius: 12px;
                    max-width: 450px; width: 90%; text-align: center;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.8); font-family: 'Poppins', sans-serif;
                }
                .modal-docs-content h2 { margin-top: 0; color: #ff5e00; font-size: 22px; }
                .modal-docs-content p { color: #ccc; font-size: 15px; line-height: 1.6; margin: 20px 0; }
                .modal-docs-actions { display: flex; gap: 15px; justify-content: center; margin-top: 25px;}
                .btn-modal { padding: 12px 20px; border-radius: 24px; border: none; font-weight: 600; cursor: pointer; transition: 0.2s; font-size: 14px;}
                
                .btn-primary-modal { background: #ff5e00; color: #fff; }
                .btn-primary-modal:hover { background: #e05300; transform: scale(1.05); }
                
                .btn-secondary-modal { background: #333; color: #aaa; }
                .btn-secondary-modal:hover { background: #dc3545; color: #fff; transform: scale(1.05); }
                
                @keyframes fadeInDocs { from { opacity: 0; } to { opacity: 1; } }
            </style>
        `;
        document.head.insertAdjacentHTML('beforeend', estilos);
    }

    // Funcionalidade dos botões internos
    document.getElementById("btnFornecerDocs").addEventListener("click", function() {
        window.location.href = "../views/usuario.php";
    });

    document.getElementById("btnNaoInteresse").addEventListener("click", function() {
        modalOverlay.remove();
    });
}