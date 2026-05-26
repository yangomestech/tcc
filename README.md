# 🎤 BeatStreet - Conectando a Cultura Hip Hop

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Status](https://img.shields.io/badge/Status-Em%20Desenvolvimento-success?style=for-the-badge)

> **Nota Académica:** Este projeto foi desenvolvido como Trabalho de Conclusão de Curso (TCC). O foco principal é fornecer uma arquitetura funcional, segura e organizada para a divulgação da cultura independente e local (Underground).

---

## 📖 Sobre o Projeto

O **BeatStreet** é uma plataforma web desenvolvida para centralizar e divulgar eventos da cultura Hip Hop, como Batalhas de Rima, Batalhas de Dança, Jams e Slams. O sistema foi pensado para atender à natureza comunitária e gratuita destes eventos, não envolvendo transações financeiras.

A plataforma permite que os utilizadores descubram eventos na sua região, interajam marcando presença ou favoritando, e confere aos organizadores a possibilidade de gerir a publicação dos seus próprios encontros culturais.

## ✨ Funcionalidades

### Para Utilizadores (Público Geral)
- 📝 **Registo e Autenticação:** Criação de conta e login seguro com palavras-passe encriptadas (Hash).
- 🔍 **Pesquisa e Exploração:** Feed dinâmico com os próximos eventos e filtros por região ou tipo.
- ⭐ **Interação:** Possibilidade de "Marcar Presença" ou adicionar um evento aos "Favoritos".
- 🗺️ **Detalhes do Evento:** Visualização de informações de localização, horários, MCs, DJs e Estilos de Dança.

### Para Organizadores
- ➕ **Criação de Eventos:** Formulário dinâmico que se adapta ao tipo de evento escolhido (ex: categorias de dança só aparecem para Jams ou Batalhas de Dança).
- 💃 **Gestão de Estilos (All Styles):** Seleção de múltiplos estilos (Breaking, Popping, Locking, etc.) para eventos de dança.

## 🛠️ Tecnologias e Arquitetura

O projeto foi construído utilizando as seguintes tecnologias e boas práticas:

- **Backend:** PHP 8.2 (Vanilla).
- **Base de Dados:** MySQL / MariaDB.
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla DOM Manipulation).
- **Infraestrutura:** Docker e Docker Compose (Imagem oficial `php:8.2-apache`).
- **Segurança & Boas Práticas:**
  - **PDO (PHP Data Objects):** Utilização estrita de *Prepared Statements* com parâmetros nomeados (`:campo`) para total prevenção contra ataques de *SQL Injection*.
  - **Transações ACID:** Uso de `beginTransaction()` e `commit()` para garantir a integridade dos dados na criação complexa de eventos (Eventos ↔ Estilos de Dança).
  - **Normalização da Base de Dados:** Estrutura relacional robusta (1:N e N:N) para garantir consistência referencial (uso rigoroso de restrições `ON DELETE CASCADE`).

## 🚀 Como Executar o Projeto (Localmente)

Graças ao ambiente em contentores, correr o projeto na sua máquina requer apenas o Docker instalado.

1. **Clone o repositório:**
   ```bash
   git clone [https://github.com/SEU_UTILIZADOR/SEU_REPOSITORIO.git](https://github.com/SEU_UTILIZADOR/SEU_REPOSITORIO.git)
