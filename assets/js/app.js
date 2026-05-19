document.addEventListener("DOMContentLoaded", () => {
    // Interações do Dashboard (Estilos)
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

    // Interações do Dashboard (Botões Hero)
    const botoes = document.querySelectorAll(".hero .btn");
    botoes.forEach((botao, index) => {
        botao.addEventListener("mouseenter", () => {
            botao.style.transform = "scale(1.10)"; // Reduzi de 1.50 pois quebrava o layout
            botao.style.boxShadow = "0 0 15px purple";
        });

        botao.addEventListener("mouseleave", () => {
            botao.style.transform = "scale(1)";
            botao.style.boxShadow = "none";
        });
    });

    // Validação Client-Side Genérica de Formulários (Auth)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const inputs = form.querySelectorAll('input[required]');
            let valid = true;
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                }
            });
            if (!valid) {
                e.preventDefault();
                alert("Preencha todos os campos obrigatórios.");
            }
        });
    });
});