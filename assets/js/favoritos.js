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
    const btnRemove = document.getElementById("btnRemoveFavorite"); // Botão do painel lateral

    // Elementos do Modal Customizado
    const modalRemove = document.getElementById('modalRemoveFavorite');
    const btnCancelRemove = document.getElementById('btnCancelRemove');
    const btnConfirmRemove = document.getElementById('btnConfirmRemove');

    // Estado global para guardar qual evento está na "mira" para ser deletado
    let eventoAlvoParaRemocao = null;

    // Cláusula de guarda para parar a execução se não houver dados
    if (!FAVORITOS_DATA || FAVORITOS_DATA.length === 0) return;

    // -----------------------------------------------------------------
    // FUNÇÕES
    // -----------------------------------------------------------------
    
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
            
            // CORREÇÃO: Alimenta o data-id do botão com o ID do evento atualizado no estado
            btnRemove.setAttribute("data-id", data.id_evento);

            // Sincronização visual da lista lateral
            items.forEach(item => item.classList.remove("active"));
            const currentItem = document.querySelector(`.playlist-item[data-index="${index}"]`);
            if (currentItem) currentItem.classList.add("active");

            // Execução da animação de entrada (Slide Up + Fade In)
            wrapper.classList.remove("sliding");
        }, 250);
    }

    // -----------------------------------------------------------------
    // INICIALIZAÇÃO E EVENTOS
    // -----------------------------------------------------------------

    // CORREÇÃO: Chamado APÓS a declaração da função para evitar erros de ciclo de vida
    updateActivePanel(0);

    // Vinculação de eventos nos itens da lista lateral (Playlist)
    items.forEach(item => {
        item.addEventListener("click", function() {
            const index = parseInt(this.getAttribute("data-index"));
            if (index === currentIndex) return;
            currentIndex = index;
            updateActivePanel(currentIndex);
        });
    });

    // 1. Intercepta o clique no painel e ABRE o modal em vez de disparar confirm()
    btnRemove.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Captura o ID injetado dinamicamente pela função updateActivePanel
        eventoAlvoParaRemocao = this.getAttribute('data-id'); 
        
        if (!eventoAlvoParaRemocao) {
            console.error("Erro: O ID do evento não foi encontrado no botão.");
            return;
        }

        modalRemove.style.display = 'flex'; // Exibe o modal
    });

    // 2. Ação de Cancelar no Modal (Reseta o estado e esconde)
    btnCancelRemove.addEventListener('click', () => {
        modalRemove.style.display = 'none';
        eventoAlvoParaRemocao = null; 
    });

    // 3. Ação de Confirmar no Modal (Executa a requisição real via Fetch)
    btnConfirmRemove.addEventListener('click', async () => {
        if (!eventoAlvoParaRemocao) return;

        // Trava os controles para evitar concorrência/duplo clique do usuário
        btnConfirmRemove.disabled = true;
        btnConfirmRemove.innerText = 'Removendo...';
        btnCancelRemove.disabled = true;

        try {
            const formData = new FormData();
            formData.append('action', 'remove');
            formData.append('id_evento', eventoAlvoParaRemocao);

            const response = await fetch('../controllers/favoritos-process.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                window.location.reload(); 
            } else {
                alert('Falha na remoção: ' + result.message);
            }
        } catch (error) {
            console.error("Erro na comunicação com o servidor:", error);
        } finally {
            // Restaura a UI caso falhe
            btnConfirmRemove.disabled = false;
            btnCancelRemove.disabled = false;
            btnConfirmRemove.innerText = 'Remover dos Favoritos';
            modalRemove.style.display = 'none';
            eventoAlvoParaRemocao = null;
        }
    });
});