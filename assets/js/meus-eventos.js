document.addEventListener("DOMContentLoaded", () => {
    configurarModalExclusao();
    configurarModalPresencas();
});

function configurarModalExclusao() {
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
}

function configurarModalPresencas() {
    const modal = document.getElementById("modalPresencasEvento");
    const nomeEventoPresencas = document.getElementById("nomeEventoPresencas");
    const contadorPresencasModal = document.getElementById("contadorPresencasModal");
    const listaPresencasModal = document.getElementById("listaPresencasModal");
    const btnFecharPresencas = document.getElementById("btnFecharPresencas");
    const botoesPresencas = document.querySelectorAll(".js-open-presencas-modal");

    const presencasEventos = window.PRESENCAS_EVENTOS || {};

    if (!modal || !nomeEventoPresencas || !contadorPresencasModal || !listaPresencasModal || !btnFecharPresencas) {
        console.error("Modal de presenças não encontrado no HTML.");
        return;
    }

    botoesPresencas.forEach((botao) => {
        botao.addEventListener("click", () => {
            const idEvento = botao.dataset.eventoId;
            const nomeEvento = botao.dataset.eventoNome || "Evento";
            const presencas = presencasEventos[idEvento] || [];

            abrirModalPresencas(nomeEvento, presencas);
        });
    });

    function abrirModalPresencas(nomeEvento, presencas) {
        nomeEventoPresencas.textContent = `"${nomeEvento}"`;

        const total = presencas.length;

        contadorPresencasModal.textContent =
            total === 1 ? "1 usuário confirmou presença" : `${total} usuários confirmaram presença`;

        listaPresencasModal.innerHTML = "";

        if (total === 0) {
            listaPresencasModal.innerHTML = `
                <div class="presencas-empty">
                    <strong>Ninguém confirmou presença ainda.</strong>
                    <span>Quando algum usuário marcar presença, ele aparecerá aqui.</span>
                </div>
            `;
        } else {
            presencas.forEach((usuario) => {
                const item = document.createElement("div");
                item.classList.add("presenca-item");

                const initials = escaparHTML(usuario.initials || "BS");
                const username = escaparHTML(usuario.username || "usuario");
                const nomeUsuario = escaparHTML(usuario.nome_usuario || usuario.username || "Usuário");

                item.innerHTML = `
                    <div class="presenca-avatar">${initials}</div>

                    <div class="presenca-info">
                        <strong>@${username}</strong>
                        <span>${nomeUsuario}</span>
                    </div>
                `;

                listaPresencasModal.appendChild(item);
            });
        }

        modal.style.display = "flex";
        document.body.classList.add("modal-open");
    }

    function fecharModalPresencas() {
        modal.style.display = "none";
        document.body.classList.remove("modal-open");

        nomeEventoPresencas.textContent = "";
        contadorPresencasModal.textContent = "";
        listaPresencasModal.innerHTML = "";
    }

    btnFecharPresencas.addEventListener("click", fecharModalPresencas);

    modal.addEventListener("click", (event) => {
        if (event.target === modal) {
            fecharModalPresencas();
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && modal.style.display === "flex") {
            fecharModalPresencas();
        }
    });
}

function escaparHTML(valor) {
    return String(valor)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}