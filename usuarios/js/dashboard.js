//Teste no console do site//
console.log("Dashboard conectado!");

const cards = document.querySelectorAll(".style-card");

//cards de estilo//


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


//Botão de cima


const botoes = document.querySelectorAll(".btn");

botoes.forEach((botao, index) => {

    botao.addEventListener("mouseenter", () => {

        botao.style.transform = "scale(1.50)";
        botao.style.boxShadow = "0 0 15px purple";

        if (index === 0) {
            botao.style.marginRight = "40px";
        }

        if (index === 1) {
            botao.style.marginLeft = "45px";
        }

    });

    botao.addEventListener("mouseleave", () => {

        botao.style.transform = "scale(1)";
        botao.style.boxShadow = "none";
        botao.style.marginRight = "10px";
        botao.style.marginLeft = "0px";

    });

});
