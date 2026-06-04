-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Tempo de geração: 22/05/2026 às 00:17
-- Versão do servidor: 10.11.16-MariaDB-ubu2204
-- Versão do PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tcc`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacao_evento`
--

CREATE TABLE `avaliacao_evento` (
  `id_avaliacao` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `nota` tinyint(4) NOT NULL,
  `comentario` text DEFAULT NULL,
  `data_avaliacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estilo_danca`
--

CREATE TABLE `estilo_danca` (
  `id_estilo_danca` int(11) NOT NULL,
  `nome_estilo` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estilo_danca`
--

INSERT INTO `estilo_danca` (`id_estilo_danca`, `nome_estilo`) VALUES
(3, 'All Styles (ou Open Style)'),
(1, 'Breaking'),
(2, 'Hip Hop Dance (ou Hip Hop Freestyle)'),
(7, 'House Dance'),
(6, 'Krump'),
(5, 'Locking'),
(4, 'Popping'),
(8, 'Waacking / Vogue');

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento`
--

CREATE TABLE `evento` (
  `id_evento` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `nome_evento` varchar(150) NOT NULL,
  `horario_evento` time NOT NULL,
  `data_evento` date NOT NULL,
  `link_evento` varchar(255) DEFAULT NULL,
  `imagem_evento` varchar(255) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `rua` varchar(100) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `mc_host` varchar(100) DEFAULT NULL COMMENT 'Mestre de Cerimônia / Host',
  `dj` varchar(100) DEFAULT NULL COMMENT 'DJ Residente ou Convidado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `evento`
--

INSERT INTO `evento` (`id_evento`, `id_usuario`, `id_tipo`, `nome_evento`, `horario_evento`, `data_evento`, `link_evento`, `imagem_evento`, `estado`, `cidade`, `cep`, `bairro`, `rua`, `numero`, `complemento`, `mc_host`, `dj`) VALUES
(2, 1, 2, 'teste', '05:04:00', '2008-02-03', NULL, NULL, 'te', 'teste', 'teste', 'teste', 'teste', 'teste', 'teste', 'teste', 'teste');

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos_evento`
--

CREATE TABLE `favoritos_evento` (
  `id_favorito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `data_favorito` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ligacao_evento_estilo`
--

CREATE TABLE `ligacao_evento_estilo` (
  `id_evento` int(11) NOT NULL,
  `id_estilo_danca` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ligacao_evento_estilo`
--

INSERT INTO `ligacao_evento_estilo` (`id_evento`, `id_estilo_danca`) VALUES
(2, 3),
(2, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ligacao_usuario_estilo`
--

CREATE TABLE `ligacao_usuario_estilo` (
  `id_usuario` int(11) NOT NULL,
  `id_estilo_danca` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `presenca`
--

CREATE TABLE `presenca` (
  `id_presenca` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_evento`
--

CREATE TABLE `tipo_evento` (
  `id_tipo` int(11) NOT NULL,
  `nome_tipo` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipo_evento`
--

INSERT INTO `tipo_evento` (`id_tipo`, `nome_tipo`) VALUES
(1, 'Batalhas de Dança'),
(3, 'Batalhas de Rima (de rua, estação, gratuita)'),
(2, 'Jams'),
(4, 'Slams');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nome_usuario` varchar(100) NOT NULL,
  `email_usuario` varchar(150) NOT NULL,
  `telefone_usuario` varchar(20) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `senha_usuario` varchar(255) NOT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `rua` varchar(100) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `tentativas_login` int(11) NOT NULL DEFAULT 0,
  `bloqueado_ate` datetime DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `username`, `nome_usuario`, `email_usuario`, `telefone_usuario`, `data_nascimento`, `senha_usuario`, `cep`, `estado`, `cidade`, `bairro`, `rua`, `numero`, `complemento`, `tentativas_login`, `bloqueado_ate`, `cpf`, `rg`) VALUES
(1, 'teste', 'teste', 'teste@gmail.com', NULL, NULL, '$2y$10$eGO.ykmm1LYQ31ilMF4fOOEC27/P/axXEWrZsoBeQZjtBJegyqWVS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(2, 'Yan', 'Yan Gomes Bahia', 'yanbahiano07@gmail.com', NULL, NULL, '$2y$10$/bIeU.IQNFnPgDoj4lCJFunoGgwTehlaghslxgJYMHF3Fkj2727VK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `avaliacao_evento`
--
ALTER TABLE `avaliacao_evento`
  ADD PRIMARY KEY (`id_avaliacao`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_evento` (`id_evento`);

--
-- Índices de tabela `estilo_danca`
--
ALTER TABLE `estilo_danca`
  ADD PRIMARY KEY (`id_estilo_danca`),
  ADD UNIQUE KEY `nome_estilo` (`nome_estilo`);

--
-- Índices de tabela `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id_evento`),
  ADD UNIQUE KEY `link_evento` (`link_evento`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_evento_tipo` (`id_tipo`);

--
-- Índices de tabela `favoritos_evento`
--
ALTER TABLE `favoritos_evento`
  ADD PRIMARY KEY (`id_favorito`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_evento` (`id_evento`);

--
-- Índices de tabela `ligacao_evento_estilo`
--
ALTER TABLE `ligacao_evento_estilo`
  ADD PRIMARY KEY (`id_evento`,`id_estilo_danca`),
  ADD KEY `id_estilo_danca` (`id_estilo_danca`);

--
-- Índices de tabela `ligacao_usuario_estilo`
--
ALTER TABLE `ligacao_usuario_estilo`
  ADD PRIMARY KEY (`id_usuario`,`id_estilo_danca`),
  ADD KEY `id_estilo_danca` (`id_estilo_danca`);

--
-- Índices de tabela `presenca`
--
ALTER TABLE `presenca`
  ADD PRIMARY KEY (`id_presenca`),
  ADD UNIQUE KEY `id_usuario_evento` (`id_usuario`,`id_evento`),
  ADD KEY `id_evento` (`id_evento`);

--
-- Índices de tabela `tipo_evento`
--
ALTER TABLE `tipo_evento`
  ADD PRIMARY KEY (`id_tipo`),
  ADD UNIQUE KEY `nome_tipo` (`nome_tipo`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email_usuario` (`email_usuario`),
  ADD UNIQUE KEY `telefone_usuario` (`telefone_usuario`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `rg` (`rg`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `avaliacao_evento`
--
ALTER TABLE `avaliacao_evento`
  MODIFY `id_avaliacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estilo_danca`
--
ALTER TABLE `estilo_danca`
  MODIFY `id_estilo_danca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `evento`
--
ALTER TABLE `evento`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `favoritos_evento`
--
ALTER TABLE `favoritos_evento`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `presenca`
--
ALTER TABLE `presenca`
  MODIFY `id_presenca` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipo_evento`
--
ALTER TABLE `tipo_evento`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avaliacao_evento`
--
ALTER TABLE `avaliacao_evento`
  ADD CONSTRAINT `avaliacao_evento_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `avaliacao_evento_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`) ON DELETE CASCADE;

--
-- Restrições para tabelas `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_evento_tipo` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_evento` (`id_tipo`) ON DELETE CASCADE;

--
-- Restrições para tabelas `favoritos_evento`
--
ALTER TABLE `favoritos_evento`
  ADD CONSTRAINT `favoritos_evento_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_evento_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ligacao_evento_estilo`
--
ALTER TABLE `ligacao_evento_estilo`
  ADD CONSTRAINT `fk_ev_est_1` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ev_est_2` FOREIGN KEY (`id_estilo_danca`) REFERENCES `estilo_danca` (`id_estilo_danca`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ligacao_usuario_estilo`
--
ALTER TABLE `ligacao_usuario_estilo`
  ADD CONSTRAINT `ligacao_usuario_estilo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `ligacao_usuario_estilo_ibfk_2` FOREIGN KEY (`id_estilo_danca`) REFERENCES `estilo_danca` (`id_estilo_danca`) ON DELETE CASCADE;

--
-- Restrições para tabelas `presenca`
--
ALTER TABLE `presenca`
  ADD CONSTRAINT `presenca_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `presenca_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
