document.addEventListener("DOMContentLoaded", () => {

    //Dropdown de localização
    const locationBox = document.querySelector('.location-box');
    const locationMenu = document.getElementById('locationMenu');
    const locationText = document.getElementById('locationSelectedText');
    const hiddenInput = document.getElementById('cidadeInput');
    const locationItems = document.querySelectorAll('.location-item');

    // Abre/Fecha menu ao clicar no botão
    locationBox.addEventListener('click', (e) => {
        if (!e.target.closest('.location-menu')) {
            locationMenu.classList.toggle('active');
        }
    });

    // Seleção de um estado/lugar
    locationItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.stopPropagation(); 
            
            const value = item.getAttribute('data-value');
            let text = item.innerText.trim();

            if (item.classList.contains('use-location')) {
                text = "Buscando...";
            }

            locationText.innerText = text;
            hiddenInput.value = value;
            locationMenu.classList.remove('active');
        });
    });

    // Fecha o dropdown se o usuário clicar em qualquer lugar fora dele
    document.addEventListener('click', (e) => {
        if (!locationBox.contains(e.target)) {
            locationMenu.classList.remove('active');
        }
    });

    // Interações de Hover (Cards e Botões)
    const cards = document.querySelectorAll(".style-card");
    cards.forEach(card => {
        card.addEventListener("mouseenter", () => {
            card.style.transform = "scale(1.08)";
            card.style.background = "linear-gradient(90deg, purple, orange)";
            card.style.color = "white";
            card.style.cursor = "pointer";
        });
        card.addEventListener("mouseleave", () => {
            card.style.transform = "scale(1)";
            card.style.background = "#111";
            card.style.color = "white";
        });
    });

    const botoes = document.querySelectorAll(".hero-actions .btn");
    botoes.forEach(botao => {
        botao.addEventListener("mouseenter", () => {
            botao.style.transform = "scale(1.10)"; 
            botao.style.boxShadow = "0 0 15px purple";
        });
        botao.addEventListener("mouseleave", () => {
            botao.style.transform = "scale(1)";
            botao.style.boxShadow = "none";
        });
    });

    // Lógica do Carrossel Coverflow (Estilo Sympla)
    const slides = document.querySelectorAll('.carousel-item');
    const indicatorContainer = document.querySelector('.carousel-indicators');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    let currentIndex = 0;
    const totalSlides = slides.length;
    let autoPlayInterval;

    if (totalSlides > 0) {
        slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            indicatorContainer.appendChild(dot);
        });

        const dots = document.querySelectorAll('.dot');

        function updateCarousel() {
            slides.forEach(slide => slide.classList.remove('active', 'prev', 'next'));
            dots.forEach(dot => dot.classList.remove('active'));

            slides[currentIndex].classList.add('active');
            dots[currentIndex].classList.add('active');

            let prevIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            let nextIndex = (currentIndex + 1) % totalSlides;

            slides[prevIndex].classList.add('prev');
            slides[nextIndex].classList.add('next');
        }

        function goToSlide(index) {
            currentIndex = index;
            updateCarousel();
            resetAutoPlay();
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateCarousel();
            resetAutoPlay();
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateCarousel();
            resetAutoPlay();
        }

        slides.forEach(slide => {
            slide.addEventListener('click', function() {
                if (this.classList.contains('prev')) {
                    prevSlide();
                } else if (this.classList.contains('next')) {
                    nextSlide();
                }
            });
        });

        prevBtn.addEventListener('click', prevSlide);
        nextBtn.addEventListener('click', nextSlide);

        function startAutoPlay() {
            autoPlayInterval = setInterval(nextSlide, 5000);
        }

        function resetAutoPlay() {
            clearInterval(autoPlayInterval);
            startAutoPlay();
        }

        updateCarousel();
        startAutoPlay();
    }

    // ========================================================
    // UX/UI: SISTEMA DE BUSCA EM TEMPO REAL (AUTOCOMPLETE)
    // ========================================================
    const inputBusca = document.getElementById('inputBusca');
    const containerSugestoes = document.getElementById('containerSugestoes');
    let debounceTimer;

    if (inputBusca && containerSugestoes) {
        inputBusca.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            const query = inputBusca.value.trim();

            if (query.length < 2) {
                containerSugestoes.innerHTML = '';
                containerSugestoes.classList.remove('active');
                return;
            }

            // Debounce de 300ms para poupar processamento no servidor
            debounceTimer = setTimeout(() => {
                // Rota direta: executado a partir do escopo de controllers/
                fetch(`../controllers/busca-sugestoes.php?q=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Erro de rede: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        containerSugestoes.innerHTML = '';

                        if (data && data.length > 0) {
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.classList.add('suggestion-item');
                                div.innerHTML = `
                                    <span class="suggestion-name">${escapeHTML(item.nome_evento)}</span>
                                    <span class="suggestion-type">${escapeHTML(item.nome_tipo)}</span>
                                `;

                                // Redirecionamento correto ao clicar na sugestão
                                div.addEventListener('click', () => {
                                    window.location.href = `../controllers/detalhe-evento.php?id=${item.id_evento}`;
                                });

                                containerSugestoes.appendChild(div);
                            });
                            containerSugestoes.classList.add('active');
                        } else {
                            containerSugestoes.classList.remove('active');
                        }
                    })
                    .catch(error => console.error('Erro ao procurar sugestões:', error));
            }, 300);
        });

        // Oculta se o utilizador clicar fora da barra de pesquisa
        document.addEventListener('click', (e) => {
            if (!inputBusca.contains(e.target) && !containerSugestoes.contains(e.target)) {
                containerSugestoes.classList.remove('active');
            }
        });

        // Reabre caso possua caracteres válidos ao focar novamente
        inputBusca.addEventListener('focus', () => {
            if (inputBusca.value.trim().length >= 2 && containerSugestoes.children.length > 0) {
                containerSugestoes.classList.add('active');
            }
        });
    }

    // Higienização contra falhas de XSS no DOM injetado
    function escapeHTML(str) {
        return str.replace(/[&<>'"]/g, 
            tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
        );
    }
});