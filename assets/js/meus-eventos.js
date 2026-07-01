document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modalExcluirEvento");
    const nomeEventoExcluir = document.getElementById("nomeEventoExcluir");
    const btnCancelar = document.getElementById("btnCancelarExclusao");
    const btnConfirmar = document.getElementById("btnConfirmarExclusao");
    const botoesExcluir = document.querySelectorAll(".js-open-delete-modal");

    let urlExclusao = null;

    if (!modal || !nomeEventoExcluir || !btnCancelar || !btnConfirmar) {
        console.error("Modal de exclusão não encontrado no HTML.");
        return;
    }

    botoesExcluir.forEach((botao) => {
        botao.addEventListener("click", (event) => {
            event.preventDefault();

            urlExclusao = botao.dataset.deleteUrl;
            const nomeEvento = botao.dataset.eventoNome || "este evento";

            if (!urlExclusao) {
                console.error("URL de exclusão não encontrada no botão.");
                return;
            }

            nomeEventoExcluir.textContent = `"${nomeEvento}"`;

            modal.style.display = "flex";
            document.body.classList.add("modal-open");
        });
    });

    function fecharModal() {
        modal.style.display = "none";
        document.body.classList.remove("modal-open");

        urlExclusao = null;
        nomeEventoExcluir.textContent = "";
    }

    btnCancelar.addEventListener("click", fecharModal);

    btnConfirmar.addEventListener("click", () => {
        if (!urlExclusao) {
            console.error("URL de exclusão não encontrada.");
            return;
        }

        window.location.href = urlExclusao;
    });

    modal.addEventListener("click", (event) => {
        if (event.target === modal) {
            fecharModal();
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && modal.style.display === "flex") {
            fecharModal();
        }
    });
});