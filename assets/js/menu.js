document.addEventListener("DOMContentLoaded", () => {
    try {
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        if (userMenuBtn && userDropdown) {
            // Abre e fecha o menu
            userMenuBtn.addEventListener('click', (e) => {
                e.preventDefault(); 
                e.stopPropagation(); 
                userDropdown.classList.toggle('active');
            });

            // Fecha ao clicar fora
            document.addEventListener('click', (e) => {
                if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('active');
                }
            });
        } else {
            console.warn("Menu de usuário não encontrado nesta página.");
        }
    } catch (error) {
        console.error("Erro no script do menu isolado:", error);
    }
});