# 🎤 BeatStreet - Conectando a Cultura Hip Hop

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Status](https://img.shields.io/badge/Status-Em%20Desenvolvimento-success?style=for-the-badge)

> **Nota Académica:** Este projeto foi desenvolvido como Trabalho de Conclusão de Curso (TCC). O foco principal é fornecer uma arquitetura funcional, segura e organizada para a divulgação da cultura independente e local (Underground).

---

## 📖 Sobre o Projeto

[cite_start]O **BeatStreet** é uma plataforma web desenvolvida para centralizar e divulgar eventos da cultura Hip Hop, como Batalhas de Rima, Batalhas de Dança, Jams e Slams[cite: 52, 350]. [cite_start]O sistema foi pensado para atender à natureza comunitária e gratuita destes eventos, não envolvendo transações financeiras[cite: 51, 52]. 

[cite_start]A plataforma permite que os utilizadores descubram eventos na sua região, interajam marcando presença ou favoritando, e confere aos organizadores a possibilidade de gerir a publicação dos seus próprios encontros culturais[cite: 351, 353].

## ✨ Funcionalidades

### Para Utilizadores (Público Geral)
- [cite_start]📝 **Registo e Autenticação:** Criação de conta e login seguro com palavras-passe encriptadas (Hash)[cite: 336, 351].
- [cite_start]🔍 **Pesquisa e Exploração:** Feed dinâmico com os próximos eventos e filtros por região ou tipo[cite: 337, 351].
- [cite_start]⭐ **Interação:** Possibilidade de "Marcar Presença" ou adicionar um evento aos "Favoritos".
- [cite_start]🗺️ **Detalhes do Evento:** Visualização de informações de localização, horários, MCs, DJs e Estilos de Dança[cite: 145, 351].

### Para Organizadores
- [cite_start]➕ **Criação de Eventos:** Formulário dinâmico que se adapta ao tipo de evento escolhido (ex: categorias de dança só aparecem para Jams ou Batalhas de Dança)[cite: 339, 341].
- [cite_start]💃 **Gestão de Estilos (All Styles):** Seleção de múltiplos estilos (Breaking, Popping, Locking, etc.) para eventos de dança[cite: 53, 55, 63].

## 🛠️ Tecnologias e Arquitetura

[cite_start]O projeto foi construído utilizando as seguintes tecnologias e boas práticas:

- [cite_start]**Backend:** PHP 8.2 (Vanilla)[cite: 277, 351].
- [cite_start]**Base de Dados:** MySQL / MariaDB[cite: 139, 351].
- [cite_start]**Frontend:** HTML5, CSS3, JavaScript (Vanilla DOM Manipulation)[cite: 340, 351].
- [cite_start]**Infraestrutura:** Docker e Docker Compose (Imagem oficial `php:8.2-apache`)[cite: 292, 351].
- **Segurança & Boas Práticas:**
  - [cite_start]**PDO (PHP Data Objects):** Utilização estrita de *Prepared Statements* com parâmetros nomeados (`:campo`) para total prevenção contra ataques de *SQL Injection*[cite: 45, 244].
  - [cite_start]**Transações ACID:** Uso de `beginTransaction()` e `commit()` para garantir a integridade dos dados na criação complexa de eventos (Eventos ↔ Estilos de Dança)[cite: 70, 196].
  - [cite_start]**Normalização da Base de Dados:** Estrutura relacional robusta (1:N e N:N) para garantir consistência referencial (uso rigoroso de restrições `ON DELETE CASCADE`)[cite: 66, 128, 136].

## 🚀 Como Executar o Projeto (Localmente)

[cite_start]Graças ao ambiente em contentores, correr o projeto na sua máquina requer apenas o Docker instalado[cite: 284, 351].

1. **Clone o repositório:**
   ```bash
   git clone [https://github.com/SEU_UTILIZADOR/SEU_REPOSITORIO.git](https://github.com/SEU_UTILIZADOR/SEU_REPOSITORIO.git)
