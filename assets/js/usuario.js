document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("profileForm");
    const modal = document.getElementById("confirmModal");
    const btnCancel = document.getElementById("btnCancel");
    const btnConfirm = document.getElementById("btnConfirm");

    // Roda os escutadores se todos os elementos estiverem em tela
    if (form && modal && btnCancel && btnConfirm) {
        
        // Segura a submissão nativa e chama o Modal
        form.addEventListener("submit", function(event) {
            event.preventDefault(); 
            modal.classList.add("active"); 
        });

        // Fecha se clicar no "Não"
        btnCancel.addEventListener("click", function() {
            modal.classList.remove("active");
        });

        // Dispara de verdade o formulário para o PHP ao clicar no "Tenho certeza"
        btnConfirm.addEventListener("click", function() {
            form.submit();
        });

        // Fecha se clicar na região escura ao redor do modal
        modal.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.classList.remove("active");
            }
        });
    }
});
