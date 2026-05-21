document.addEventListener("DOMContentLoaded", () => {// Lógica do Dropdown de Localização
const locationBox = document.querySelector('.location-box');
const locationMenu = document.getElementById('locationMenu');
const locationText = document.getElementById('locationSelectedText');
const hiddenInput = document.getElementById('cidadeInput');
const locationItems = document.querySelectorAll('.location-item');

// Abre/Fecha menu ao clicar no botão
locationBox.addEventListener('click', (e) => {
    // Evita fechar imediatamente se clicar em um item da lista
    if (!e.target.closest('.location-menu')) {
        locationMenu.classList.toggle('active');
    }
});

// Seleção de um estado/lugar
locationItems.forEach(item => {
    item.addEventListener('click', (e) => {
        e.stopPropagation(); // Impede o clique de borbulhar e acionar o locationBox
        
        const value = item.getAttribute('data-value');
        let text = item.innerText.trim();

        // Se for a opção de GPS, mais pra frente você integra a API de Geolocation.
        // Por hora, só muda o texto.
        if (item.classList.contains('use-location')) {
            text = "Buscando...";
            // TODO: Inserir navigator.geolocation.getCurrentPosition()
        }

        // Atualiza a UI e o Input Hidden pro PHP
        locationText.innerText = text;
        hiddenInput.value = value;
        
        // Fecha o dropdown
        locationMenu.classList.remove('active');
    });
});

// Fecha o dropdown se o usuário clicar em qualquer lugar fora dele
document.addEventListener('click', (e) => {
    if (!locationBox.contains(e.target)) {
        locationMenu.classList.remove('active');
    }
});
    // Interações de Hover (Cards e Botões) mantidas da sua versão original
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
        // Criar indicadores (bolinhas) dinamicamente
        slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            indicatorContainer.appendChild(dot);
        });

        const dots = document.querySelectorAll('.dot');

        function updateCarousel() {
            // Remove todas as classes de controle
            slides.forEach(slide => slide.classList.remove('active', 'prev', 'next'));
            dots.forEach(dot => dot.classList.remove('active'));

            // Aplica estado ativo
            slides[currentIndex].classList.add('active');
            dots[currentIndex].classList.add('active');

            // Calcula index anterior e próximo (looping circular)
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

        // Permite clicar nos cards laterais para navegar (igual ao Sympla)
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

        // Auto-play otimizado
        function startAutoPlay() {
            autoPlayInterval = setInterval(nextSlide, 5000);
        }

        function resetAutoPlay() {
            clearInterval(autoPlayInterval);
            startAutoPlay();
        }

        // Inicializa o estado do carrossel
        updateCarousel();
        startAutoPlay();
    }
}); 
