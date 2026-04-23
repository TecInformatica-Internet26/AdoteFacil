-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql102.infinityfree.com
-- Tempo de geração: 23/04/2026 às 13:55
-- Versão do servidor: 11.4.10-MariaDB
-- Versão do PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `if0_40698155_adotefacil`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(80) DEFAULT NULL,
  `cpf` char(11) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefone` varchar(11) DEFAULT NULL,
  `whatsapp` varchar(11) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `token_redefinir` varchar(255) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nome`, `cpf`, `data_nasc`, `email`, `telefone`, `whatsapp`, `estado`, `cidade`, `senha`, `foto`, `token_redefinir`, `token_expira`) VALUES
(1, 'Admin', '02135456415', '2004-01-02', 'admadote@gmail.com', '48948949849', '45645645645', 'PE', 'Belo Jardim', '$2y$10$IncRs7Ji2zyS9hG9rRiwd.c3K9gSqIXxxchuHFAyNkWNKJFMDOwqm', NULL, NULL, NULL),
(3, 'Miguel Miguel', '12345678999', '2025-12-05', 'miguel@gmail.com', '00000000000', '99999999999', 'CE', 'Acopiara', '$2y$10$K.E9E9cy1uv7INZXpS6m9.c8/A9s4rsHKptiTQWRMjT3rcPsFbzH.', NULL, NULL, NULL),
(4, 'João Victor Rodrigues Frazão', '89191818901', '2008-09-09', 'joaozinho231@gmail.com', '99991162534', '99991632534', 'MA', 'Imperatriz', '$2y$10$AugVzgx8X.2FJ5eGcQdT8uwk7RkgaJGGguTMNpt663BFfrMWlLt0W', NULL, NULL, NULL),
(5, 'Laura ', '83838383878', '2008-05-08', 'lauradasilvaaa5@gmail.com', '99992018158', '99992018158', 'MA', 'Imperatriz', '$2y$10$SciWpnS93YdQAT7TcUEhYu1SsQS578p7WzJ0ox5ANYzxIwKP.6HK.', 'usr_6941a0af1d2b3.jpg', NULL, NULL),
(9, 'Hitallo Sousa', '06582847450', '2006-08-07', 'felix@gmail.com', '99991776157', '99991776157', 'MA', 'Imperatriz', '$2y$10$4RDENlxcIgJGSeyLx3knFuiSlxGmWjso4t7M/g/crRyDZyXbZ0/9q', '6942feb5392c8_RDT_20251216_162108.gif', NULL, NULL),
(12, 'Jardel', '77373738383', '2025-12-24', 'resendejardeldasilva@gmail.com', '99991630199', '44949946466', 'MA', 'Imperatriz', '$2y$10$sixGhhiiVCMHASdNrIsgOubsoK77LToD6a1SqiPsl1uyfNL5733hK', NULL, NULL, NULL),
(13, 'Brenno Vitor Otaviano', '17669996382', '2004-12-15', 'bainhino28@gmail.com', '99981063256', '99981063256', 'MA', 'Caxias', '$2y$10$GpjJVIysrgLA6LxtsQt1MeIGMlqWO9BJJH2py78uXcwwWtQeqQ2T.', 'usr_6941a1f156b6e.jpg', NULL, NULL),
(14, 'Manu Maia', '12121212121', '2000-02-10', 'manu@gmail.com', '99991915412', '99991915412', 'BA', 'Pau Brasil', '$2y$10$VYHWLsJYUgVumYto3spAWu8DYVjoGSiHmY6Wk6SZ6QDwsrDAuVDuS', 'usr_6941a2538ec50.jpg', NULL, NULL),
(15, 'Vinícius Aires', '12312321231', '2000-11-21', 'vinnyaires@gmail.com', '12313221313', '63992764814', 'MA', 'Imperatriz', '$2y$10$zxaOXUTAYFH4uxMBYrVSs.HztZjMO2RCECGN637Kk4PJoAfY1XFam', 'usr_6941a260c4fbd.jpg', NULL, NULL),
(16, 'karolynne paulo de andrade', '04687008380', '2007-06-07', 'karolynnepaulo70@gmail.com', '99991523777', '99991523777', 'MA', 'Imperatriz', '$2y$10$IM.kkg/Mp9aY44D9YHsezOebhxmkYgSyuqRb6t/mp3Tx6NjBuv5pO', 'usr_6941a27be1dc3.jpg', NULL, NULL),
(17, 'otávio', '77777777777', '2006-10-24', 'windowsnototavio@gmail.com', '99991062090', '99991062090', 'SC', 'Balneário Arroio do Silva', '$2y$10$lUqtvtXvVexAzyy8aXqlbOCMtHJcprGBC87FxPRZGLKejRXJj7uWe', 'usr_6941a2d02cf46.jpg', NULL, NULL),
(18, 'Hernandes Matheus Alves Dos Santos ', '11108973345', '2007-03-18', 'hernandesmatheus07@gmail.com', '99992032875', '99991148710', 'MA', 'Imperatriz', '$2y$10$EcmdCLDGs/uKjbE2aKxk8e4qQVVxRGZoKbq7zNpF34iiVoBQ/3psG', '6941ac9bba036.png', NULL, NULL),
(19, 'Guilherme Alexandre ', '11122233344', '2007-12-20', 'guilhermeale532@gmail.com', '99991063218', '99991063218', 'MA', 'Imperatriz', '$2y$10$KKeAxtonwVuc6VOfL6edwu/IlM97C/q8PT0NUG0QDKeLeo8RdFCb2', 'usr_6941a43447626.webp', NULL, NULL),
(21, 'Isaac', '12312312315', '2000-05-02', 'isaacenzo126@gmail.com', '12312313213', '45445654654', 'MA', 'Imperatriz', '$2y$10$b3iPzBK575OHn0Wh5SYWUu2Wt0kj/0kXCHBw6/qbKDk7KjHrtl/hi', NULL, NULL, NULL),
(22, 'Anna Beatriz Nunes ', '63245501331', '2007-04-06', 'beatriznunessb@gmail.com', '99991989046', '99991989046', 'MA', 'Imperatriz', '$2y$10$V6PySJ7wLN6rSJwKqhrah.2Uevp6JVyTMqHK.SfUmCwC36z0rmGwe', NULL, NULL, NULL),
(23, 'Hshdjdjd', '00000373749', '2023-12-06', 'bdbdbsb@gmail.com', '99999555625', '65626262326', 'BA', 'Alagoinhas', '$2y$10$H1Alq7MJNj4nu7RJaveXZ.Zgv0k/XCxe/bjQsPFJTJYZWmrgvIVw.', NULL, NULL, NULL),
(24, 'Yasmim rayra', '10192293303', '2007-02-11', 'yasmimrayra782@gmail.com', '99991302382', '99991302382', 'MA', 'Imperatriz', '$2y$10$Nh4NfS1UomFSslxTm0Ox1.piLg/7J6IEQtvOKPIIkkpxgyL7Ze4hW', NULL, NULL, NULL),
(25, 'MAYSA DA SILVA MARINHO', '08649396399', '2009-04-01', 'maysamarinho2020@gmail.com', '99991668967', '99991668967', 'MA', 'Imperatriz', '$2y$10$Z8es1lKy.MxEnvdYkBsRVupugvRyTGaW8kbKG0iF94Nr4Kv/CcMcu', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico`
--

CREATE TABLE `historico` (
  `id_historico` int(11) NOT NULL,
  `id_pet` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `status_pet` varchar(100) DEFAULT NULL,
  `data_status` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `historico`
--

INSERT INTO `historico` (`id_historico`, `id_pet`, `id_cliente`, `status_pet`, `data_status`) VALUES
(1, 1, 3, 'disponivel', NULL),
(2, 2, 3, 'disponivel', NULL),
(3, 3, 9, 'disponivel', NULL),
(5, 5, 13, 'disponivel', NULL),
(6, 6, 15, 'disponivel', NULL),
(7, 7, 13, 'disponivel', NULL),
(8, 8, 5, 'disponivel', NULL),
(9, 9, 9, 'disponivel', NULL),
(10, 10, 14, 'disponivel', NULL),
(11, 11, 16, 'disponivel', NULL),
(12, 12, 17, 'disponivel', NULL),
(13, 13, 12, 'disponivel', NULL),
(14, 14, 9, 'disponivel', NULL),
(15, 15, 17, 'disponivel', NULL),
(16, 16, 9, 'disponivel', NULL),
(17, 17, 18, 'disponivel', NULL),
(18, 18, 19, 'disponivel', NULL),
(19, 19, 18, 'disponivel', NULL),
(20, 20, 18, 'disponivel', NULL),
(22, 20, 18, 'adotado', NULL),
(24, 23, 21, 'disponivel', NULL),
(25, 23, 21, 'adotado', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pet`
--

CREATE TABLE `pet` (
  `id_pet` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `nome` varchar(20) DEFAULT NULL,
  `genero` varchar(20) DEFAULT NULL,
  `idade` int(2) DEFAULT NULL,
  `especie` varchar(100) DEFAULT NULL,
  `porte` varchar(30) DEFAULT NULL,
  `raca` varchar(50) DEFAULT NULL,
  `situacao` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `statusPet` varchar(20) DEFAULT 'disponivel'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `pet`
--

INSERT INTO `pet` (`id_pet`, `id_cliente`, `nome`, `genero`, `idade`, `especie`, `porte`, `raca`, `situacao`, `foto`, `statusPet`) VALUES
(1, 3, 'Gatinho', 'Macho', 3, 'Gato', 'Médio', 'SRD', 'Vacinado', '../../IMG/adote/69419fa3ddb01_1000224842.jpg', 'disponivel'),
(2, 3, 'Koda', 'Macho', 1, 'Gato', 'Médio', 'SRD', 'Vacinado', '../../IMG/adote/6941a011782c2_1000224820.jpg', 'disponivel'),
(3, 9, 'Gui', 'Macho', 2, 'Gato', 'Médio', 'SRD', 'Vacinado', '../../IMG/adote/6941a1ea5bc81_1000166954.jpg', 'disponivel'),
(5, 13, 'Thor', 'Macho', 2, 'Cachorro', 'Médio', 'Pug', 'Vacinado', '../../IMG/adote/6941a2565bbdd_pug.jpg', 'disponivel'),
(6, 15, 'Totó', 'Macho', 1, 'Cachorro', 'Grande', 'Pitbull', 'Vacinado', '../../IMG/adote/6941a290c67f4_711413356050763.jpg', 'disponivel'),
(7, 13, 'lulu', 'Fêmea', 1, 'Gato', 'Pequeno', 'Himalaio', 'Vacinado', '../../IMG/adote/6941a2b79fb15_lulu.jpg', 'disponivel'),
(8, 5, 'Anely', 'Fêmea', 1, 'Gato', 'Pequeno', 'Gato malhado', 'Vacinado', '../../IMG/adote/6941a2bb78081_1000047970.jpg', 'disponivel'),
(9, 9, 'Jake', 'Macho', 3, 'Cachorro', 'Médio', 'Vira-lata', 'Vacinado', '../../IMG/adote/6941a2bd47b87_1000166955.jpg', 'disponivel'),
(10, 14, 'Kira', 'Fêmea', 1, 'Cachorro', 'Pequeno', 'shih tzu', 'Vacinado e Castrado', '../../IMG/adote/6941a2be2b0a2_images.jpg', 'disponivel'),
(11, 16, 'Emma', 'Fêmea', 4, 'Cachorro', 'Pequeno', 'Welsh Corgi Pembroke', 'Vacinado', '../../IMG/adote/6941a2e03b155_kk.webp', 'disponivel'),
(12, 17, 'aslam', 'Macho', 3, 'Cachorro', 'Médio', 'chow chow', 'Vacinado', '../../IMG/adote/6941a2f9510e1_chow.jpg', 'disponivel'),
(13, 12, 'Pérola ', 'Fêmea', 1, 'Cachorro', 'Pequeno', 'Pomerânia', 'Vacinado', '../../IMG/adote/6941a34e8db20_1000426765.jpg', 'disponivel'),
(14, 9, 'Tod', 'Macho', 1, 'Cachorro', 'Pequeno', 'SRD', 'Vacinado', '../../IMG/adote/6941a350a2fed_1000166956.jpg', 'disponivel'),
(15, 17, 'jupi', 'Macho', 5, 'Cachorro', 'Grande', 'pastor alemão', 'Castrado', '../../IMG/adote/6941a425b6ea6_pastor.jpg', 'disponivel'),
(16, 9, 'Fernando ', 'Macho', 2, 'Cachorro', 'Médio', 'Caramelo ', 'Vacinado', '../../IMG/adote/6941a42ae420d_1000166957.jpg', 'disponivel'),
(17, 18, 'Zeus', 'Macho', 2, 'Cachorro', 'Grande', 'Rottweiler', 'Vacinado', '../../IMG/adote/6941a47f589df_rottweiler-p.webp', 'disponivel'),
(18, 19, 'Rex', 'Macho', 2, 'Cachorro', 'Grande', 'Golden Retriever', 'Vacinado', '../../IMG/adote/6941a4f7dc6be_auau.webp', 'disponivel'),
(19, 18, 'Rambo', 'Macho', 2, 'Cachorro', 'Grande', 'pitbull ', 'Vacinado e Castrado', '../../IMG/adote/6941a60ae08ff_racas_de_cachorros_pitbull_20289_600_square.jpg', 'adotado'),
(20, 18, 'Luke', 'Macho', 3, 'Cachorro', 'Grande', 'pitbull ', 'Vacinado', '../../IMG/adote/6941a712d8279_cão-masculino-de-pitbull-terrier-do-americano-fotografia-da-adoção-animal-estimação-118498445.webp', 'adotado'),
(23, 21, 'Peludinho', 'Macho', 2, 'Cachorro', 'Médio', 'SRD', 'Vacinado', '../../IMG/adote/6941bbd2d1fa5_CaneCorso.jpg', 'adotado');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `telefone` (`telefone`),
  ADD UNIQUE KEY `whatsapp` (`whatsapp`);

--
-- Índices de tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id_historico`),
  ADD KEY `id_pet` (`id_pet`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`id_pet`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id_historico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `pet`
--
ALTER TABLE `pet`
  MODIFY `id_pet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `historico`
--
ALTER TABLE `historico`
  ADD CONSTRAINT `historico_ibfk_1` FOREIGN KEY (`id_pet`) REFERENCES `pet` (`id_pet`),
  ADD CONSTRAINT `historico_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Restrições para tabelas `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `pet_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
