/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.3.2-MariaDB, for Android (aarch64)
--
-- Host: localhost    Database: tcc
-- ------------------------------------------------------
-- Server version	12.3.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `avaliacao_evento`
--

DROP TABLE IF EXISTS `avaliacao_evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `avaliacao_evento` (
  `id_avaliacao` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `nota` tinyint(4) NOT NULL,
  `comentario` text DEFAULT NULL,
  `data_avaliacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_avaliacao`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_evento` (`id_evento`),
  CONSTRAINT `avaliacao_evento_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `avaliacao_evento_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacao_evento`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `avaliacao_evento` WRITE;
/*!40000 ALTER TABLE `avaliacao_evento` DISABLE KEYS */;
/*!40000 ALTER TABLE `avaliacao_evento` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `comentario_evento`
--

DROP TABLE IF EXISTS `comentario_evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comentario_evento` (
  `id_comentario` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `data_comentario` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_comentario`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_evento` (`id_evento`),
  CONSTRAINT `fk_comentario_evento` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`) ON DELETE CASCADE,
  CONSTRAINT `fk_comentario_user` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentario_evento`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `comentario_evento` WRITE;
/*!40000 ALTER TABLE `comentario_evento` DISABLE KEYS */;
INSERT INTO `comentario_evento` VALUES
(9,1,42,'Muito bom esse evento','2026-06-16 00:38:50'),
(12,15,43,'gostei bastante da ultima edição','2026-06-23 04:00:12'),
(13,16,42,'muito legal','2026-07-01 00:40:01'),
(14,17,42,'O evento foi incrivel','2026-07-01 02:43:26'),
(15,28,57,'🔥🔥🔥🔥','2026-07-02 17:29:39'),
(16,28,43,'muito bom🔥🔥🔥🔥','2026-07-02 17:30:09'),
(17,29,57,'Estou muito ansioso','2026-07-02 17:53:10');
/*!40000 ALTER TABLE `comentario_evento` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `estilo_danca`
--

DROP TABLE IF EXISTS `estilo_danca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estilo_danca` (
  `id_estilo_danca` int(11) NOT NULL AUTO_INCREMENT,
  `nome_estilo` varchar(80) NOT NULL,
  PRIMARY KEY (`id_estilo_danca`),
  UNIQUE KEY `nome_estilo` (`nome_estilo`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estilo_danca`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `estilo_danca` WRITE;
/*!40000 ALTER TABLE `estilo_danca` DISABLE KEYS */;
INSERT INTO `estilo_danca` VALUES
(3,'All Styles'),
(1,'Breaking'),
(2,'Hip Hop Dance'),
(7,'House Dance'),
(6,'Krump'),
(5,'Locking'),
(4,'Popping'),
(8,'Waacking / Vogue');
/*!40000 ALTER TABLE `estilo_danca` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `evento`
--

DROP TABLE IF EXISTS `evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `evento` (
  `id_evento` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `nome_evento` varchar(150) NOT NULL,
  `horario_evento` time NOT NULL,
  `data_evento` date NOT NULL,
  `imagem_evento` varchar(255) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `rua` varchar(100) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `mc_host` varchar(100) DEFAULT NULL COMMENT 'Mestre de Cerimônia / Host',
  `dj` varchar(100) DEFAULT NULL COMMENT 'DJ Residente ou Convidado',
  `descricao` text DEFAULT NULL COMMENT 'Descrição detalhada do evento (atrações, cronograma, regras, etc.)',
  PRIMARY KEY (`id_evento`),
  KEY `id_usuario` (`id_usuario`),
  KEY `fk_evento_tipo` (`id_tipo`),
  CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_evento_tipo` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_evento` (`id_tipo`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evento`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `evento` WRITE;
/*!40000 ALTER TABLE `evento` DISABLE KEYS */;
INSERT INTO `evento` VALUES
(42,1,2,'Open HIP HOP Training','19:00:00','2026-07-15','uploads/eventos/evento_6a309855ba198.png','SP','São Paulo','01231-010','Santa Cecilia','Rua Dr. Gabriel dos Santos','88',NULL,NULL,NULL,'Jean Claudio',NULL,'Treino aberto de HipHop dancing todas as quartas feiras a partir das 19h00.'),
(43,4,3,'Batalha da aldeia (BDA)','20:00:00','2026-08-21','uploads/eventos/evento_6a309a3a9826e.png','SP','São Paulo','03681020','Barueri',' Avenida Guilherme Perereca Guglielme','s/n','Praça dos Estudantes',NULL,NULL,'Bob13','Dj Duh França','Um evento de Batalha de rima tradicional do centro de São Paulo'),
(44,4,1,'Summer Dance Forever','20:00:00','2026-08-17','uploads/eventos/evento_6a309b1e0ecb2.png','SP','São Paulo','03681020','vila esperança','parara ti bumm','2',NULL,NULL,NULL,NULL,NULL,'Batalha de dança e aulas de variados estilos da cultura hip hop'),
(51,21,3,'Batalha do Vale','21:00:00','2026-07-03','uploads/eventos/evento_6a46a121afcee.png','SP','Sao Paulo','03678030','Vila São Francisco','Noel José da Silva','515',NULL,-20.78363070,-49.40771380,'Thiago_dancer','Dj Cleo','🔥BATALHA DE RIMA! 🔥\r\n\r\nAs ruas falam, o beat chama e o microfone vira arma. Chegou a hora de provar quem tem a melhor caneta, a resposta mais afiada e a presença mais forte na roda.\r\n\r\n#BatalhaDeRima #RapNacional #Freestyle #HipHop #CulturaDeRua #MC #Improviso #Rima #Batalha #U'),
(54,22,3,'Batalha Da Matrix','21:30:00','2026-07-15','uploads/eventos/evento_6a46810b243cc.jpg','Sp','São paulo','358358353','Diadema','Marco Antonio de Azevedo','42',NULL,NULL,NULL,'Knust','Blakes','VENHA CURTIR O MELHOR EVENTO DE RIMA DO ABC'),
(55,23,3,'Batalha do Coliseu','19:00:00','2026-08-14','uploads/eventos/evento_6a4681dfc1a7e.jpg','RJ','Rio de Janeiro','20040007','Centro','Praça Mário Lago (Buraco do Lume)','S/N','Próximo ao Terminal Menezes Cortes e à Estação Carioca do Metrô',NULL,NULL,'Allan Freestyle',NULL,'A Batalha do Coliseu é uma das maiores e mais tradicionais batalhas de rima do Rio de Janeiro, reunindo semanalmente MCs, artistas e amantes da cultura hip-hop para noites de improviso, criatividade e muita disputa. Com entrada gratuita e uma energia única, o evento é um ponto de encontro para quem quer prestigiar o freestyle, descobrir novos talentos e vivenciar de perto a cena do rap carioca.'),
(56,24,1,'Batalha do Chinelo','14:00:00','2026-07-03','uploads/eventos/evento_6a46830a150be.jpg','SP','Sao Paulo','027490040','A.E Carvalho','General Felisberto Castro','87',NULL,NULL,NULL,'B-boy pelezinho','Negresko','Beat pesado, chão livre e muito estilo.\r\n\r\nChegou a hora de mostrar o que você treina, trocar ideia com quem vive a cultura e fortalecer a cena. Seja para competir, fazer uma cypher ou só acompanhar o nível da galera, todo mundo é bem-vindo.\r\n\r\n🎧 DJ no comando\r\n🔥 Cyphers abertas\r\n🏆 Batalhas de Breaking\r\n\r\nRespeito, técnica, criatividade e atitude. O resto fica por conta da música.\r\n\r\n#Breaking #HipHop #Bboy #Bgirl #StreetCulture #DanceBattle #Cypher'),
(57,25,3,'Batalha da Leste (BDL)','19:15:00','2026-07-03','uploads/eventos/evento_6a4683f007675.jpg','SP','São Paulo','08210110','Itaquera','Largo da Matriz','85-119','Passarela entre o Terminal Itaquera, a Estação Corinthians–Itaquera e a Neo Química Arena.',NULL,NULL,'Bob 13',NULL,'A Batalha da Leste é uma das maiores e mais tradicionais batalhas de rima de São Paulo. Há mais de uma década fortalecendo a cultura hip-hop na Zona Leste, reúne semanalmente MCs, artistas e amantes do freestyle em um ambiente de criatividade, improviso e muita energia. Com entrada gratuita, é referência nacional na cena do rap e do freestyle.'),
(58,26,4,'Slam Clube da Luta','18:45:00','2026-07-03','uploads/eventos/evento_6a4695a455ca1.jpg','MG','Belo Horizonte','30120000','Centro','Rua Aarão Reis','542','Teatro Espanca',NULL,NULL,'Rogério Coelho',NULL,'O Slam Clube da Luta é a primeira competição de poesia falada de Minas Gerais, promovendo encontros de palavra, performance e resistência em Belo Horizonte.'),
(59,26,4,'Slam Contrataque','21:30:00','2026-12-31','uploads/eventos/evento_6a469670cccfc.jpg','PR','Curitiba','80020000','Centro Histórico','Largo da Ordem','S/N','Praça do Cavalo Babão / Fonte da Memória',NULL,NULL,'Coletivo Slam Contrataque',NULL,'O Slam Contrataque é a primeira comunidade de slam de Curitiba, promovendo poesia falada, literatura marginal e expressão periférica em praças públicas da capital paranaense.'),
(60,26,4,'Slam da Guilhermina','18:00:00','2026-07-03','uploads/eventos/evento_6a4696f0cbaf9.png','SP','São Paulo','03542000','Vila Guilhermina','Rua Astorga','774','Saída do Metrô Guilhermina-Esperança',NULL,NULL,'Emerson Alcalde, Cristina Assunção, Uilian Chapéu e Rodrigo Motta',NULL,'O Slam da Guilhermina é uma das batalhas de poesia falada mais tradicionais de São Paulo, reunindo poetas, artistas e público em uma roda de expressão, resistência e literatura periférica na Zona Leste.'),
(61,26,4,'Slam das Minas','20:30:00','2026-07-03','uploads/eventos/evento_6a4697a2ae0e9.jpg','SP','São Paulo','01311200','Bela Vista / Jardim Paulista','Avenida Paulista','1063','Livraria da Vila – unidade Paulista',NULL,NULL,'Pam Araújo, Carolina Peixoto e Bruna Mara',NULL,'O Slam das Minas SP é uma batalha de poesia com protagonismo de mulheres e pessoas trans, criando um espaço de fala, escuta, arte e resistência através da palavra.'),
(62,27,2,'Que se Dance — Jam de Improvisação','18:15:00','2026-07-03','uploads/eventos/evento_6a469c3570fc9.jpg','MG','Belo Horizonte','30160000','Centro','Praça Rui Barbosa','S/N','Praça da Estação, em frente ao Museu de Artes e Ofícios',NULL,NULL,'Plataforma Que se Dance',NULL,'A Jam de Improvisação do Que se Dance transforma a Praça da Estação em um espaço aberto de movimento, convidando pessoas com ou sem experiência em dança para participar de uma vivência coletiva, livre e criativa.'),
(63,27,2,'Jam Mercedes Party','15:45:00','2026-07-03','uploads/eventos/evento_6a469e92d16f7.jpg','SP','São Paulo','01035000','Centro','Avenida São João','473','Galeria Olido — Vitrine da Dança',NULL,NULL,'Mercedes Ladies e Kika Souza',NULL,'A Jam Mercedes Party é um encontro de dança, DJ e cultura hip-hop voltado à celebração da presença de mulheres, pessoas trans, não binárias e da comunidade ligada às danças urbanas. O evento divulgado teve JAM de dança, discotecagem e entrada gratuita na Galeria Olido.'),
(66,29,3,'Batalha da Norte','19:30:00','2026-07-03','uploads/eventos/evento_6a46a7c2eb78d.jpg','SP','São Paulo','02034000','Santana','Praça Margarida de Albuquerque Gimenez','S/N','Região próxima ao Metrô Santana.',NULL,NULL,'Shade',NULL,'A Batalha da Norte é uma das maiores batalhas de rima de São Paulo, ocupando semanalmente a Zona Norte com duelos de freestyle, presença forte da cultura hip-hop e MCs de alto nível. O evento acontece em praça pública, com entrada gratuita e grande participação do público');
/*!40000 ALTER TABLE `evento` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `favoritos_evento`
--

DROP TABLE IF EXISTS `favoritos_evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `favoritos_evento` (
  `id_favorito` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `data_favorito` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_favorito`),
  UNIQUE KEY `uk_usuario_evento_fav` (`id_usuario`,`id_evento`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_evento` (`id_evento`),
  CONSTRAINT `favoritos_evento_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `favoritos_evento_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoritos_evento`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `favoritos_evento` WRITE;
/*!40000 ALTER TABLE `favoritos_evento` DISABLE KEYS */;
INSERT INTO `favoritos_evento` VALUES
(12,4,43,'2026-06-16 00:35:36'),
(14,4,42,'2026-06-16 00:36:16'),
(15,1,43,'2026-06-16 00:37:53'),
(16,1,42,'2026-06-16 00:38:12'),
(18,14,43,'2026-06-19 00:16:28'),
(19,15,43,'2026-06-23 03:59:59'),
(21,15,42,'2026-06-23 04:00:59'),
(23,16,42,'2026-07-01 02:25:55'),
(24,17,42,'2026-07-01 02:42:58'),
(26,9,42,'2026-07-02 00:31:42'),
(28,29,57,'2026-07-02 17:52:46'),
(29,29,56,'2026-07-02 18:05:23');
/*!40000 ALTER TABLE `favoritos_evento` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `ligacao_evento_estilo`
--

DROP TABLE IF EXISTS `ligacao_evento_estilo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ligacao_evento_estilo` (
  `id_evento` int(11) NOT NULL,
  `id_estilo_danca` int(11) NOT NULL,
  PRIMARY KEY (`id_evento`,`id_estilo_danca`),
  KEY `id_estilo_danca` (`id_estilo_danca`),
  CONSTRAINT `fk_ev_est_1` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`) ON DELETE CASCADE,
  CONSTRAINT `fk_ev_est_2` FOREIGN KEY (`id_estilo_danca`) REFERENCES `estilo_danca` (`id_estilo_danca`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ligacao_evento_estilo`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `ligacao_evento_estilo` WRITE;
/*!40000 ALTER TABLE `ligacao_evento_estilo` DISABLE KEYS */;
INSERT INTO `ligacao_evento_estilo` VALUES
(56,1),
(62,1),
(42,2),
(44,2),
(56,2),
(62,2),
(63,2),
(62,3),
(44,4),
(62,4),
(44,5),
(56,5),
(62,5),
(62,6),
(44,7),
(62,7),
(44,8),
(62,8);
/*!40000 ALTER TABLE `ligacao_evento_estilo` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `ligacao_usuario_estilo`
--

DROP TABLE IF EXISTS `ligacao_usuario_estilo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ligacao_usuario_estilo` (
  `id_usuario` int(11) NOT NULL,
  `id_estilo_danca` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_estilo_danca`),
  KEY `id_estilo_danca` (`id_estilo_danca`),
  CONSTRAINT `ligacao_usuario_estilo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `ligacao_usuario_estilo_ibfk_2` FOREIGN KEY (`id_estilo_danca`) REFERENCES `estilo_danca` (`id_estilo_danca`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ligacao_usuario_estilo`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `ligacao_usuario_estilo` WRITE;
/*!40000 ALTER TABLE `ligacao_usuario_estilo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ligacao_usuario_estilo` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `presenca`
--

DROP TABLE IF EXISTS `presenca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `presenca` (
  `id_presenca` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  PRIMARY KEY (`id_presenca`),
  UNIQUE KEY `id_usuario_evento` (`id_usuario`,`id_evento`),
  KEY `id_evento` (`id_evento`),
  CONSTRAINT `presenca_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `presenca_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presenca`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `presenca` WRITE;
/*!40000 ALTER TABLE `presenca` DISABLE KEYS */;
INSERT INTO `presenca` VALUES
(33,1,42),
(32,1,43),
(47,1,57),
(31,4,42),
(29,4,43),
(36,15,43),
(38,16,42),
(39,17,42),
(41,20,44),
(44,27,43),
(46,27,44),
(43,27,55),
(42,27,57),
(45,27,61),
(48,28,43),
(50,28,44),
(51,28,55),
(49,28,61),
(54,29,56),
(53,29,57);
/*!40000 ALTER TABLE `presenca` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `tipo_evento`
--

DROP TABLE IF EXISTS `tipo_evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_evento` (
  `id_tipo` int(11) NOT NULL AUTO_INCREMENT,
  `nome_tipo` varchar(80) NOT NULL,
  PRIMARY KEY (`id_tipo`),
  UNIQUE KEY `nome_tipo` (`nome_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_evento`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `tipo_evento` WRITE;
/*!40000 ALTER TABLE `tipo_evento` DISABLE KEYS */;
INSERT INTO `tipo_evento` VALUES
(1,'Batalhas de Dança'),
(3,'Batalhas de Rima'),
(2,'Jams'),
(4,'Slams');
/*!40000 ALTER TABLE `tipo_evento` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
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
  `rg` varchar(20) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email_usuario` (`email_usuario`),
  UNIQUE KEY `telefone_usuario` (`telefone_usuario`),
  UNIQUE KEY `cpf` (`cpf`),
  UNIQUE KEY `rg` (`rg`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES
(1,'teste','teste da silva','teste@gmail.com','13122123',NULL,'$2y$10$eGO.ykmm1LYQ31ilMF4fOOEC27/P/axXEWrZsoBeQZjtBJegyqWVS','21313124',NULL,'Sampas',NULL,'rua penha','22','8',0,NULL,'5545425225','329409238',NULL,NULL),
(3,'testedois','testedois','testedois@gmail.com',NULL,NULL,'$2y$10$h7xozcbZjxWJADZadMzRU.2D.bE69T66fo0A0W3kmBlNNE2qW6xT6',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),
(4,'blodskald','maicon','nathansouzaneves@gmail.com','dfdfdf',NULL,'$2y$10$KpdSw0/LhtIGJvDGejHbfu9NY2wXg62Mpy8pj7QWIRPrUSKyXWjV6','03681020','SP','São Paulo','Burgo Paulista','sdfdfsfsdf','asd','dfd',0,NULL,'asd','asd',NULL,NULL),
(6,'Nathn Foda','nathan souza neves','nathansouzaneves44@gmail.com',NULL,NULL,'$2y$10$YOzKwKlVCojT4wQ.zLqK4.A6N5cJ.jkQ5k2nuJWcTR14SBOmHh.iK',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),
(7,'morketid','Carlos augusto','nathansouzaneves1dd5@gmail.com','(11) 98765-4321',NULL,'$2y$10$omQNlsFdyzEJPOIUJImPB.oEhTjolxEVB9g/PpmFr9LaBoYtpf3QS','03681020','SP','São Paulo','Burgo Paulista','furtaram o demonoio','666','dfsdf',0,NULL,'527.184.930-61','48.392.761-2',NULL,NULL),
(8,'Yan','teste','yanbahiano07@gmail.com','11913384739',NULL,'$2y$10$dlzawlNExdcOzcRpQEwbLOdS1DSNdqd4yufz8FYU/1/NuPkVQ5AFW','03680-000','SP','São Paulo','Burgo pau','rua ur aura','1111',NULL,0,NULL,'55444714876','888888888',NULL,NULL),
(9,'louco','loucao','louco@gmail.com','5345345',NULL,'$2y$10$rt1w2mpNjOMyhkfIs2E4quZollQEJtGGZyzFPi2546YToYf1edEqW','345345345','RJ','gdfgdf','gdgdgd','gfdfhdfhedf','463','fhdfg',0,NULL,'34594858395','5454534534',NULL,NULL),
(10,'BDN','Alfredo Gonçalvez','alfredodanorte@gmail.com','11922949483',NULL,'$2y$10$oNqKDcb6d33nrqlEygCylez0fMuPubmHzxQguvrWbkdhD5YacK0te','04485000','SP','Sao Paulo','penha','rua da penha','123','apt 64',0,NULL,'50350248046','3424524542',NULL,NULL),
(11,'cacilds','cacilds. souza','cacilds@gmail.com','94839483948',NULL,'$2y$10$HzJMnMPb82ZRT9MNJY3uCe9ieeyQMAUqbdmiTyLLcRr.jolTLAQ/K','93849384','sp','sao','maind','fldienfie9','43443','4343',0,NULL,'33847837583','3943434834',NULL,NULL),
(12,'cacilhas','cacilhas souza','cacilhas@gmail.com','23523523523',NULL,'$2y$10$nWvyi0302F/b6GWibouvc.7j66i1dSJ21YC/M8QhWMubCLTc0ihj.','32535235','wd','hftdgdgy','46353535','dgdgdsgdsgdsg','5353','ddgdg',0,NULL,'95793593759','2352525235',NULL,NULL),
(13,'erro','erro da silva','erro@erro.com','32097490327',NULL,'$2y$10$IRl4tkTcPc8TQurd6XpVcOXXLI3HyA8ZU4XqbVYQf0hSFqt3KotjK','93458248','sp','samap','cnaoa','dosihudvh','7593','CA',0,NULL,'95493809527','0346890689',NULL,NULL),
(14,'canalha','canalha','canalha@gmail.com','46464646464',NULL,'$2y$10$3l0n5t5gOEjxEnWzrnUD5u6dtr.rt7rS6w1ubLgmUjfoi0i6UXLn6','64646464','sd','fefsdfsefe','fsdfdsfdsfsd','65gfhfhdfhfhd','43434','234234234',0,NULL,'53246436346','6464646464',NULL,NULL),
(15,'fofoleti','fofis','fofoleti@gmail.com','52352352352',NULL,'$2y$10$xUqp1z9wtBySyfImBDKjseClymug4AyrhKHCl9LCRS1oBdrdMyQ7e','523523523','ge','gegege','gegegeg','djkiehgee','353','fegege',0,NULL,'59468434694','535352353',NULL,NULL),
(16,'apresentação','nathan','testar@gmail.com','46464646757',NULL,'$2y$10$8.tETbF2AM4kGEIQ11/E/eE7vzdDTezBUn57GSp8dO41ozuDdUvkG','757546564','SP','fhfhfhfhfrh','hfhfhfhf','hfdhrhrh','756','hfhfhfdhfdhf',2,NULL,'46464643646','4646464646',NULL,NULL),
(17,'guto','Augusto Bezerra','augusto@gmail.com','6546546465',NULL,'$2y$10$79DnnDQkD5VIr/fKaUOw/.by2VMV3X11nVFIqLfBVSQLGv7.ocDW.','354654654','SP','sgsdgdgd','gsfgsdgsd','efsdgfsdgsdg','35','sdgsdgsd',0,NULL,'5464654','3356465464',NULL,NULL),
(18,'michael','michael jackson','michaeljackson@gmail.com','34634634636',NULL,'$2y$10$mbYLRLUI3PJjiy8.3pKg7.FMrH2RPGUVo3QggvLZWz7SNoKeqgzee','03681020','SP','Sao Paulo','Burgo paulista','Conceição do castelo','97',NULL,0,NULL,'34573467346','3463463636',NULL,NULL),
(19,'prince','principio','prince@gmail.com','45745745745',NULL,'$2y$10$79YYHBD1/lpWCkZiLi9HzOiGDezuhrpmXeGUxHcebiMC5bhUGq0S6','03681020','SP','Sao Paulo','Burgo paulista','Conceição do castelo','97',NULL,0,NULL,'47477574574','5745745745',NULL,NULL),
(20,'lenon','John Lenon','lenon@gmail.com','44378659607',NULL,'$2y$10$YIBtqZB9.WT.Zs3JCDrG7ebCvPxG5YVS1f.WsdUvikXbFUr4Cog.y','03681020','SP','Sao Paulo','Burgo paulista','Conceição do castelo','97',NULL,0,NULL,'45683685678','9635486985',-23.52233300,-46.48463520),
(21,'Thiago_dancer','Thiago Caio','Thiaguinho@gmail.com','37354849568',NULL,'$2y$10$JCELSiVyT5dFZifNViVR1uwobd9TA0jBbpvS0s7ZVAcv1N0V7FOyC','0368000','SP','Sao Paulo','Burgo paulista','José Silva Alcântara Filho','114',NULL,0,NULL,'98203626479','9028692786',-23.52110460,-46.48789700),
(22,'Jackson_404','jackson kumbaya','jacksonkumbaya@gmail.com','03947692323',NULL,'$2y$10$dtI/ADdSTQMIkLWuHlIBze.EV/izVASwkUH5S4e4BjR62lkMyk3pS','338593767','SP','dfhdfh','bfdgh','Clauddkkgeksj','473','majdgi',0,NULL,'38469570575','5795957957',NULL,NULL),
(23,'bdcoliseu','batalha do coliseu','bdcoliseu@gmail.com','21921992377',NULL,'$2y$10$PYSPq4hJZVZGgkzNy3LJve2Vko//.ewH5ILCu3zjg.wjfbrw/e4UK',NULL,'RJ','Rio de Janeiro','Centro','Rua Romeu Casagrande','123','Praça',0,NULL,'54926551205','5284026440',NULL,NULL),
(24,'B-boy_pelezinho','pele da silva','bboy@gmail.com','26374584689',NULL,'$2y$10$1UzZoNhcgCmOpiAuoQ2T8.S0KSldk7ZKCxPj0dc1895VSErqCRBb.','756457547','SP','hrheujerjr','Capão Redondo','jghdiskjgskfj','2477','hhrhd',0,NULL,'89375493785','9292924785',NULL,NULL),
(25,'bdleste','Bob 13','Bob13@gmail.com','11917745653',NULL,'$2y$10$UKs2s4YtQgveOMjl6BDud.CWguSyNkifgwj.ZZYaI/.QzplCewkRy','08210110','SP','São Paulo','Itaquera','Largo da Matriz','85–99','Passarela entre o Terminal Itaquera, a Estação Corinthians–Itaquera e a Neo Química Arena',0,NULL,'55439832496','5217579324',NULL,NULL),
(26,'Slam','slams da silva','slam@gmail.com','11933192388',NULL,'$2y$10$5XLmteRRspctk9PGFaOe6.BkAemqgS.xLnp9RbhpGJ57ymVuTTJgW','034880900','SP','Sao Paulo','Centro','rua slam','123',NULL,0,NULL,'57354928788','5188474899',NULL,NULL),
(27,'Jams','jam da silva','jam@gmail.com','11933874777',NULL,'$2y$10$4tbnZQluLgIUbroFelC3BehA9XBadkJJPgIM/C2cy657h5NBVlKkm','034849990','MG','Belo Horizonte','mg','Praça Rui Barbosa','S/N','Praça da Estação, em frente ao Museu de Artes e Ofícios',0,NULL,'56324577746','5289946620',NULL,NULL),
(28,'Jose','Jose silva','jose@gmail.com',NULL,NULL,'$2y$10$Wmf1ydtbUjhSq9rne2VAf.c33Ll5A4gt.3krY8NYOz0Ldw7vGJEEq',NULL,'SP',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),
(29,'CesarMC','Cesar da Silva','cesarmc@gmail.com','11913787454',NULL,'$2y$10$zqK/ok3TQIWPwIV4/8CIe.oWViwAb36Huq6UAug/jo1xMfy1qXRnm','034789000','SP',NULL,NULL,NULL,NULL,NULL,0,NULL,'54836496362','5173840375',NULL,NULL);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-07-04 14:26:09
