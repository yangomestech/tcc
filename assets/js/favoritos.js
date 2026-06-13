document.addEventListener("DOMContentLoaded", () => {
    // Abstração de controle de estado local
    let currentIndex = 0;
    const items = document.querySelectorAll(".playlist-item");
    const wrapper = document.getElementById("animatedWrapper");

    // Elementos do Painel de Exibição
    const panelImg = document.getElementById("panelImage");
    const panelType = document.getElementById("panelType");
    const panelTitle = document.getElementById("panelTitle");
    const panelDate = document.getElementById("panelDate");
    const panelTime = document.getElementById("panelTime");
    const panelLocation = document.getElementById("panelLocation");
    const panelPresences = document.getElementById("panelPresences");
    const panelDesc = document.getElementById("panelDescription");
    const panelLink = document.getElementById("panelLink");
    const btnRemove = document.getElementById("btnRemoveFavorite");

    if (!FAVORITOS_DATA || FAVORITOS_DATA.length === 0) return;

    // Função de reidratação estrutural com animação controlada
    function updateActivePanel(index) {
        const data = FAVORITOS_DATA[index];
        if (!data) return;

        // Ativação da animação de saída (Slide Down + Fade Out)
        wrapper.classList.add("sliding");

        setTimeout(() => {
            // Atualização de nós de texto e propriedades de mídia
            panelImg.src = data.imagem_processada;
            panelImg.alt = data.nome_evento;
            panelType.textContent = data.nome_tipo;
            panelTitle.textContent = data.nome_evento;
            panelDate.textContent = data.data_formatada;
            panelTime.textContent = data.horario_formatado;
            
            // Tratamento de concatenação de endereço completo
            const localCompleto = `${data.rua}, ${data.numero} - ${data.bairro}, ${data.cidade} - ${data.estado}`;
            panelLocation.textContent = localCompleto;
            
            panelPresences.textContent = data.total_presencas;
            panelDesc.textContent = data.descricao || "Sem descrição disponível para este evento.";
            panelLink.href = `../controllers/detalhe-evento.php?id=${data.id_evento}`;

            // Sincronização visual da lista lateral
            items.forEach(item => item.classList.remove("active"));
            const currentItem = document.querySelector(`.playlist-item[data-index="${index}"]`);
            if (currentItem) currentItem.classList.add("active");

            // Execução da animação de entrada (Slide Up + Fade In)
            wrapper.classList.remove("sliding");
        }, 250);
    }

    // Vinculação de eventos nos itens da lista (Playlist)
    items.forEach(item => {
        item.addEventListener("click", function() {
            const index = parseInt(this.getAttribute("data-index"));
            if (index === currentIndex) return;
            currentIndex = index;
            updateActivePanel(currentIndex);
        });
    });

    // Interceptação e tratamento assíncrono para remoção de favoritos
    if (btnRemove) {
        btnRemove.addEventListener("click", async () => {
            const currentData = FAVORITOS_DATA[currentIndex];
            if (!currentData) return;

            if (!confirm(`Deseja remover "${currentData.nome_evento}" dos seus favoritos?`)) return;

            // Transição para estado de carregamento visual
            btnRemove.disabled = true;
            btnRemove.textContent = "Removendo...";

            try {
                const formData = new FormData();
                formData.append("action", "remove");
                formData.append("id_evento", currentData.id_evento);

                const response = await fetch("favoritos-process.php", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Recarga estratégica da página para recalcular índices e estados vazios
                    window.location.reload();
                } else {
                    alert(result.message || "Falha ao remover item.");
                    btnRemove.disabled = false;
                    btnRemove.textContent = "Remover dos Favoritos";
                }
            } catch (error) {
                alert("Erro de comunicação com o servidor.");
                btnRemove.disabled = false;
                btnRemove.textContent = "Remover dos Favoritos";
            }
        });
    }

    // Inicialização da primeira carga estrutural do painel
    updateActivePanel(currentIndex);
});