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

