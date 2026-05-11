-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 15/04/2026 às 19:59
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

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

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento`
--

CREATE TABLE `evento` (
  `id_evento` int(11) NOT NULL,
  `id_produtor` int(11) NOT NULL,
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
  `complemento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Estrutura para tabela `ligacao_modalidade`
--

CREATE TABLE `ligacao_modalidade` (
  `id_evento` int(11) NOT NULL,
  `id_modalidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ligacao_tipo_evento`
--

CREATE TABLE `ligacao_tipo_evento` (
  `id_evento` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Estrutura para tabela `logs`
--

CREATE TABLE `logs` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `tipo_acao` varchar(50) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `ip_usuario` varchar(45) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `data_hora` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `logs`
--

INSERT INTO `logs` (`id_log`, `id_usuario`, `tipo_acao`, `descricao`, `ip_usuario`, `user_agent`, `data_hora`) VALUES
(1, 10, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 21:58:11'),
(2, 10, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 21:58:22'),
(3, 10, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 21:58:23'),
(4, 10, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 21:58:24'),
(5, 10, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 21:58:29'),
(6, 10, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 21:59:14'),
(7, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(8, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(9, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(10, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(11, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(12, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(13, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(14, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(15, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(16, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(17, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(18, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(19, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(20, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(21, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(22, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(23, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(24, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(25, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(26, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(27, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(28, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(29, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(30, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(31, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(32, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(33, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(34, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(35, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(36, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(37, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(38, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(39, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(40, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(41, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(42, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(43, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(44, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(45, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(46, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(47, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(48, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(49, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(50, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(51, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(52, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(53, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(54, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(55, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(56, 17, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(57, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(58, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(59, 17, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(60, 17, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(61, 17, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(62, 17, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(63, 17, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(64, 17, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(65, 17, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(66, 17, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(67, 17, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(68, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(69, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(70, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(71, NULL, 'LOGIN_FALHA', 'Tentativa de login com campos vazios', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(72, 17, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(73, 17, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(74, 17, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (Hydra)', '2026-04-13 21:59:47'),
(75, 10, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 22:02:52'),
(76, NULL, 'LOGIN_FALHA', 'Falha no login: usuário ou e-mail não encontrado', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 22:03:02'),
(77, NULL, 'LOGIN_FALHA', 'Falha no login: usuário ou e-mail não encontrado', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 22:08:13'),
(78, 10, 'LOGIN_BLOQUEADO', 'Usuário bloqueado temporariamente por excesso de tentativas', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 22:08:20'),
(79, 11, 'LOGIN_FALHA', 'Falha no login: senha incorreta', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 22:09:04'),
(80, 11, 'LOGIN_SUCESSO', 'Usuário autenticado com sucesso', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 22:09:14');

-- --------------------------------------------------------

--
-- Estrutura para tabela `modalidade`
--

CREATE TABLE `modalidade` (
  `id_modalidade` int(11) NOT NULL,
  `nome_modalidade` varchar(80) NOT NULL
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
-- Estrutura para tabela `produtor`
--

CREATE TABLE `produtor` (
  `id_produtor` int(11) NOT NULL,
  `nome_produtor` varchar(100) NOT NULL,
  `RG_produtor` varchar(20) NOT NULL,
  `CPF_produtor` varchar(14) NOT NULL,
  `email_produtor` varchar(150) NOT NULL,
  `telefone_produtor` varchar(20) NOT NULL,
  `senha_produtor` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtor`
--

INSERT INTO `produtor` (`id_produtor`, `nome_produtor`, `RG_produtor`, `CPF_produtor`, `email_produtor`, `telefone_produtor`, `senha_produtor`, `username`, `data_criacao`) VALUES
(1, '', '', '', '', '', '$2y$10$345hzXHFr0UowetudcUw5uZZlsR.eJI9taGuy5/DBFKN3tdYGwL0O', '137', '2026-03-21 22:58:01'),
(3, 'teste1', '1242141532', '55444714876', 'teste1@gmail.com', '143134314', '$2y$10$cMGC1X1wKOMQBeQWh/BprOhrzsrOmDZRDd4NRB9PrfRPz.rJ77k9S', 'teste1900', '2026-04-01 00:14:00'),
(5, 'teste da silva', '55555555', '55555555555', 'teste2@gmail.com', '11938458582', '$2y$10$Kw9o9JbxBUcBEuno9ykH3.5dbCXqFOchM1fk3ffFFj9iZuVP198/q', 'teste2', '2026-04-03 23:32:04');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_evento`
--

CREATE TABLE `tipo_evento` (
  `id_tipo` int(11) NOT NULL,
  `nome_tipo` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `bloqueado_ate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `username`, `nome_usuario`, `email_usuario`, `telefone_usuario`, `data_nascimento`, `senha_usuario`, `cep`, `estado`, `cidade`, `bairro`, `rua`, `numero`, `complemento`, `tentativas_login`, `bloqueado_ate`) VALUES
(1, 'NathanChupaEngole', 'Nathan Chupador da Silva', 'engolemuito@mail.com', '11969696969', '1969-09-06', '$2y$10$.h1j.H6k/7DuI3WGoHMN6OiIf9r7QBblr1bSqd.w0ziuane74v1nq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(10, 'teste', 'teste', 'teste@gmail.com', NULL, NULL, '$2y$10$wFeQV9yztAWXBrWnqT1VIOqjKrDpnX2qO8BCbpQ9LtUWmALoIaDPG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, '2026-04-13 22:18:20'),
(11, 'belinhatopzera', 'Yasmim Nathaly Venancio Pires', 'yasmim.nathaly29@gmail.com', NULL, NULL, '$2y$10$v4G/1BRfsuPJUcm5e/6Z6uVT44dMT3hSuxMhNc.iTkunTZ4zJ02Xm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(12, 'teste2', 'teste2', 'teste2@gmail.com', NULL, NULL, '$2y$10$3hkMz0Et2.pijY1MHfhQ.OGIyKcyj5nDqxyKFCgGyDTUmQGWsNk1.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(13, 'Katia', 'Katia Duarte', 'katiaduarte@gmail.com', NULL, NULL, '$2y$10$Us9OLVwt6mpMbdbmjbMt9ePpohPhctYsi2Dokz.FqUL26xWt0unkK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(14, 'Robertao', 'Roberto da Silva', 'roberto@gmail.com', NULL, NULL, '$2y$10$Bfv/I2YDnZalRLHGSN8G6Op.RxlxwAvhmBOKnor5RrFpDre8vRPpe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(15, 'Rodrigo', 'Rodrigo da Silva', 'rodrigo@gmail.com', NULL, NULL, '$2y$10$7rqon/LpW.XHHekc97e5.uZ02f9SB4WcW43pJPaerfOdeCDb7WC7K', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(16, 'Marcela', 'Marcela da Silva', 'marcela@gmail.com', NULL, NULL, '$2y$10$aFD7Hws1B0230Jw7OUpK4uXPF3JusJYQgKDTOiEDOqK/vz2Nn345u', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(17, 'Nerd', 'Nerd da Silva', 'nerd@gmail.com', NULL, NULL, '$2y$10$4FaOvlVHKdq.1MSsLlbWFO1C0IGJhh2n4t4XSPwHJoAIdhGIedR9W', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2026-04-13 22:09:47');

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
  ADD KEY `id_produtor` (`id_produtor`);

--
-- Índices de tabela `favoritos_evento`
--
ALTER TABLE `favoritos_evento`
  ADD PRIMARY KEY (`id_favorito`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_evento` (`id_evento`);

--
-- Índices de tabela `ligacao_modalidade`
--
ALTER TABLE `ligacao_modalidade`
  ADD PRIMARY KEY (`id_evento`,`id_modalidade`),
  ADD KEY `id_modalidade` (`id_modalidade`);

--
-- Índices de tabela `ligacao_tipo_evento`
--
ALTER TABLE `ligacao_tipo_evento`
  ADD PRIMARY KEY (`id_evento`,`id_tipo`),
  ADD KEY `id_tipo` (`id_tipo`);

--
-- Índices de tabela `ligacao_usuario_estilo`
--
ALTER TABLE `ligacao_usuario_estilo`
  ADD PRIMARY KEY (`id_usuario`,`id_estilo_danca`),
  ADD KEY `id_estilo_danca` (`id_estilo_danca`);

--
-- Índices de tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_id_usuario` (`id_usuario`),
  ADD KEY `idx_tipo_acao` (`tipo_acao`),
  ADD KEY `idx_data_hora` (`data_hora`);

--
-- Índices de tabela `modalidade`
--
ALTER TABLE `modalidade`
  ADD PRIMARY KEY (`id_modalidade`),
  ADD UNIQUE KEY `nome_modalidade` (`nome_modalidade`);

--
-- Índices de tabela `presenca`
--
ALTER TABLE `presenca`
  ADD PRIMARY KEY (`id_presenca`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`,`id_evento`),
  ADD KEY `id_evento` (`id_evento`);

--
-- Índices de tabela `produtor`
--
ALTER TABLE `produtor`
  ADD PRIMARY KEY (`id_produtor`),
  ADD UNIQUE KEY `RG_produtor` (`RG_produtor`),
  ADD UNIQUE KEY `CPF_produtor` (`CPF_produtor`),
  ADD UNIQUE KEY `email_produtor` (`email_produtor`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `telefone_produtor` (`telefone_produtor`) USING BTREE;

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
  ADD UNIQUE KEY `telefone_usuario` (`telefone_usuario`);

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
  MODIFY `id_estilo_danca` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `evento`
--
ALTER TABLE `evento`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `favoritos_evento`
--
ALTER TABLE `favoritos_evento`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de tabela `modalidade`
--
ALTER TABLE `modalidade`
  MODIFY `id_modalidade` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `presenca`
--
ALTER TABLE `presenca`
  MODIFY `id_presenca` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtor`
--
ALTER TABLE `produtor`
  MODIFY `id_produtor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `tipo_evento`
--
ALTER TABLE `tipo_evento`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avaliacao_evento`
--
ALTER TABLE `avaliacao_evento`
  ADD CONSTRAINT `avaliacao_evento_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `avaliacao_evento_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`);

--
-- Restrições para tabelas `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`id_produtor`) REFERENCES `produtor` (`id_produtor`);

--
-- Restrições para tabelas `favoritos_evento`
--
ALTER TABLE `favoritos_evento`
  ADD CONSTRAINT `favoritos_evento_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `favoritos_evento_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`);

--
-- Restrições para tabelas `ligacao_modalidade`
--
ALTER TABLE `ligacao_modalidade`
  ADD CONSTRAINT `ligacao_modalidade_ibfk_1` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`),
  ADD CONSTRAINT `ligacao_modalidade_ibfk_2` FOREIGN KEY (`id_modalidade`) REFERENCES `modalidade` (`id_modalidade`);

--
-- Restrições para tabelas `ligacao_tipo_evento`
--
ALTER TABLE `ligacao_tipo_evento`
  ADD CONSTRAINT `ligacao_tipo_evento_ibfk_1` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`),
  ADD CONSTRAINT `ligacao_tipo_evento_ibfk_2` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_evento` (`id_tipo`);

--
-- Restrições para tabelas `ligacao_usuario_estilo`
--
ALTER TABLE `ligacao_usuario_estilo`
  ADD CONSTRAINT `ligacao_usuario_estilo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `ligacao_usuario_estilo_ibfk_2` FOREIGN KEY (`id_estilo_danca`) REFERENCES `estilo_danca` (`id_estilo_danca`);

--
-- Restrições para tabelas `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `fk_logs_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `presenca`
--
ALTER TABLE `presenca`
  ADD CONSTRAINT `presenca_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `presenca_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
