document.addEventListener("DOMContentLoaded", () => {
    
    // 1. Lógica de exibição Condicional de Estilos de Dança
    const tipoSelect = document.getElementById('id_tipo');
    const blocoDanca = document.getElementById('bloco_estilos_danca');
    const checkboxesDanca = document.querySelectorAll('.checkbox-estilo');

    tipoSelect.addEventListener('change', function() {
        const text = this.options[this.selectedIndex].text.toLowerCase();
        // Se a opção selecionada for Dança ou Jam, mostramos os estilos
        if(text.includes('dança') || text.includes('jam')) {
            blocoDanca.style.display = 'block';
        } else {
            blocoDanca.style.display = 'none';
            checkboxesDanca.forEach(cb => cb.checked = false); // Limpa as opções ocultas
        }
    });

    // 2. Data Default: Amanhã
    const dataInput = document.getElementById('data_evento');
    const amanha = new Date();
    amanha.setDate(amanha.getDate() + 1);
    dataInput.value = amanha.toISOString().split('T')[0];

    // 3. Horários (Select de 15 em 15 min) & Auto-Select
    const horarioSelect = document.getElementById('horario_evento');
    let agora = new Date();
    
    // Calcula os próximos 15 minutos arredondados
    let minutosAtuais = agora.getMinutes();
    let resto = 15 - (minutosAtuais % 15);
    let minutosAlvo = minutosAtuais + resto;
    let horaAlvo = agora.getHours();
    
    if (minutosAlvo >= 60) {
        minutosAlvo -= 60;
        horaAlvo += 1;
    }
    if (horaAlvo >= 24) horaAlvo = 0;

    const formatTime = (h, m) => `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
    const tempoAlvoStr = formatTime(horaAlvo, minutosAlvo);

    for(let h = 0; h < 24; h++) {
        for(let m = 0; m < 60; m += 15) {
            let timeStr = formatTime(h, m);
            let option = document.createElement('option');
            option.value = timeStr + ":00"; // Salva no banco com segundos
            option.text = timeStr;
            
            // Marca o alvo calculado (ex: se entrou 11:46, marca 12:00)
            if (timeStr === tempoAlvoStr) {
                option.selected = true;
            }
            horarioSelect.appendChild(option);
        }
    }

    // 4. Preview da Imagem de Upload (Modo Sympla, aspecto seguro)
    const inputImagem = document.getElementById('imagem_evento');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('preview-image');
    const uploadText = document.getElementById('upload-text');

    inputImagem.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                uploadText.style.display = 'none';
                previewContainer.style.borderStyle = 'solid';
                previewContainer.style.borderColor = '#f97316';
            }
            reader.readAsDataURL(file);
        } else {
            previewImage.style.display = 'none';
            previewImage.src = '';
            uploadText.style.display = 'flex';
            previewContainer.style.borderStyle = 'dashed';
            previewContainer.style.borderColor = '#333';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const radiosTipo = document.querySelectorAll('input[name="id_tipo"]');
    const blocoDanca = document.getElementById('bloco_estilos_danca');
    const checkboxesDanca = document.querySelectorAll('.checkbox-estilo');

    function gerenciarEstilosDanca() {
        const selecionado = document.querySelector('input[name="id_tipo"]:checked');
        if (!selecionado) return;

        const idTipo = parseInt(selecionado.value, 10);
        
        // Tipos: 1 = Dança, 2 = Jam. Ambos usam estilos de dança.
        // Tipos: 3 = Rima, 4 = Slam. Não usam.
        if (idTipo === 1 || idTipo === 2) {
            blocoDanca.style.display = 'block';
        } else {
            blocoDanca.style.display = 'none';
            // Zera as opções para evitar envio acidental de dados ocultos
            checkboxesDanca.forEach(cb => cb.checked = false);
        }
    }

    // Executa no load da página (crucial para a tela de Editar Evento)
    gerenciarEstilosDanca();

    // Adiciona o listener para reagir aos cliques do usuário
    radiosTipo.forEach(radio => {
        radio.addEventListener('change', gerenciarEstilosDanca);
    });
});

document.addEventListener("DOMContentLoaded", function() {
    
// =========================================
// LÓGICA DE HORÁRIOS DINÂMICOS & CUSTOM SELECT
// =========================================
const inputData = document.getElementById('data_evento');
const inputHorarioReal = document.getElementById('horario_evento'); // Input Hidden
const wrapperSelect = document.getElementById('custom_time_wrapper');
const triggerSelect = document.getElementById('time_trigger');
const triggerText = triggerSelect ? triggerSelect.querySelector('span') : null;
const optionsList = document.getElementById('time_options_list');

if (inputData && inputHorarioReal && wrapperSelect) {
    
    // 1. Abrir/Fechar o Dropdown
    triggerSelect.addEventListener('click', function() {
        wrapperSelect.classList.toggle('open');
    });

    // 2. Fechar se clicar fora do componente
    document.addEventListener('click', function(e) {
        if (!wrapperSelect.contains(e.target)) {
            wrapperSelect.classList.remove('open');
        }
    });

    // 3. Função que gera a lista de horários
    function atualizarHorarios() {
        const horarioSalvo = inputHorarioReal.getAttribute('data-salvo');
        const dataEscolhida = inputData.value;
        
        optionsList.innerHTML = ''; // Limpa a lista atual
        
        const agora = new Date();
        const isHoje = !dataEscolhida || (dataEscolhida === agora.toISOString().split('T')[0]);
        
        let horaInicio = 0;
        let minutoInicio = 0;

        if (isHoje) {
            horaInicio = agora.getHours();
            minutoInicio = Math.ceil(agora.getMinutes() / 15) * 15;
            if (minutoInicio === 60) {
                horaInicio++;
                minutoInicio = 0;
            }
        }

        let encontrouSalvo = false;
        let opcoesGeradas = 0;

        for (let h = 0; h < 24; h++) {
            for (let m = 0; m < 60; m += 15) {
                if (isHoje && (h < horaInicio || (h === horaInicio && m < minutoInicio))) {
                    continue;
                }

                const horaStr = h.toString().padStart(2, '0');
                const minStr = m.toString().padStart(2, '0');
                const tempoStr = `${horaStr}:${minStr}`;

                // Cria o <li>
                const li = document.createElement('li');
                li.textContent = tempoStr;
                li.dataset.value = tempoStr;

                // Se for o salvo/selecionado
                if (horarioSalvo === tempoStr || inputHorarioReal.value === tempoStr) {
                    li.classList.add('selected');
                    triggerText.textContent = tempoStr;
                    inputHorarioReal.value = tempoStr;
                    encontrouSalvo = true;
                }

                // Evento de clique na opção
                li.addEventListener('click', function() {
                    // Remove selected dos outros
                    const todosLis = optionsList.querySelectorAll('li');
                    todosLis.forEach(item => item.classList.remove('selected'));
                    
                    // Adiciona no clicado
                    this.classList.add('selected');
                    
                    // Atualiza display e input real
                    triggerText.textContent = this.dataset.value;
                    inputHorarioReal.value = this.dataset.value;
                    
                    // Fecha dropdown
                    wrapperSelect.classList.remove('open');
                });

                optionsList.appendChild(li);
                opcoesGeradas++;
            }
        }

        // Se for edição e o horário do banco já passou (não está na lista)
        if (horarioSalvo && !encontrouSalvo && dataEscolhida) {
            const liExtra = document.createElement('li');
            liExtra.textContent = `${horarioSalvo} (Horário Atual)`;
            liExtra.dataset.value = horarioSalvo;
            liExtra.classList.add('selected');
            
            triggerText.textContent = horarioSalvo;
            inputHorarioReal.value = horarioSalvo;

            liExtra.addEventListener('click', function() {
                const todosLis = optionsList.querySelectorAll('li');
                todosLis.forEach(item => item.classList.remove('selected'));
                this.classList.add('selected');
                triggerText.textContent = this.dataset.value;
                inputHorarioReal.value = this.dataset.value;
                wrapperSelect.classList.remove('open');
            });

            optionsList.insertBefore(liExtra, optionsList.firstChild);
        }

        // UX: Se não tiver horário
        if (opcoesGeradas === 0 && (!horarioSalvo || !encontrouSalvo)) {
            const liVazio = document.createElement('li');
            liVazio.textContent = "Sem horários hoje";
            liVazio.style.pointerEvents = "none";
            optionsList.appendChild(liVazio);
            triggerText.textContent = "Sem horários hoje";
            inputHorarioReal.value = "";
        } else if (!inputHorarioReal.value && !horarioSalvo) {
            triggerText.textContent = "Selecione um horário...";
        }
    }

    // Gatilhos
    inputData.addEventListener('change', function() {
        // Ao trocar a data, limpa o horário selecionado para obrigar nova escolha
        inputHorarioReal.value = ""; 
        atualizarHorarios();
    });
    
    // Executa ao iniciar a página
    atualizarHorarios();
}
});

// =========================================
// LÓGICA DO MODAL DE EXCLUSÃO
// =========================================
// O onclick do botão de excluir lá no HTML precisa ser trocado para "abrirModalExclusao()"
function confirmarExclusao(idEvento) {
    const modal = document.getElementById('modalExclusao');
    if (modal) modal.style.display = 'flex';
}

function fecharModal() {
    const modal = document.getElementById('modalExclusao');
    if (modal) modal.style.display = 'none';
}

function executarExclusao(idEvento) {
    document.getElementById('delete_id_evento').value = idEvento;
    document.getElementById('form-delete').submit();
}