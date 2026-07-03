-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 02/07/2026 às 02:54
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
-- Banco de dados: `leilao_de_carros`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carros`
--

CREATE TABLE `carros` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL,
  `cor` varchar(50) DEFAULT NULL,
  `km` int(11) DEFAULT NULL,
  `preco_inicial` decimal(10,2) NOT NULL,
  `preco_atual` decimal(10,2) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carros`
--

INSERT INTO `carros` (`id`, `nome`, `marca`, `modelo`, `ano`, `cor`, `km`, `preco_inicial`, `preco_atual`, `imagem`, `descricao`, `ativo`, `criado_em`, `preco`, `admin_id`) VALUES
(8, 'Corolla', 'Toyota', 'edan', 2026, 'Cinza', 12, 90.00, 137000.00, NULL, NULL, 1, '2026-07-01 02:09:31', 90000.00, 3),
(9, 'Fiat Uno', 'Fiat', 'Com Escada', 2019, 'Branco', 80, 16.00, 16000.00, NULL, NULL, 1, '2026-07-01 03:02:08', 16000.00, 3),
(10, 'Nissan Skyline GT-R', 'Nissan', 'Skyline GT-R R34', 2002, 'Azul Bayside', 78, 2.80, 220800.00, NULL, NULL, 1, '2026-07-01 16:17:35', 2.80, 3),
(11, '2002 Toyota Supra RZ-S Twin Turbo', 'Toyota', 'Supra', 2002, 'Branco', 12800, 120.00, 120000.00, NULL, NULL, 1, '2026-07-01 16:21:03', 120.00, 3),
(12, 'Ford Mustang Boss', 'Ford', 'Boss', 1969, 'Vermelho', 127000, 87.00, 87000.00, NULL, NULL, 1, '2026-07-01 16:24:13', 87.00, 3),
(13, 'Opala SS', 'Chevrolet', 'Opala SS', 1978, 'Verde', 98, 180000.00, 180000.00, NULL, NULL, 1, '2026-07-01 16:28:00', 180000.00, 3),
(14, 'dodge charger r/t', 'Dodge', 'Charger R/T', 1971, 'Rox', 85000, 450000.00, 450000.00, NULL, NULL, 1, '2026-07-01 16:32:17', 450000.00, 3),
(15, 'Porsche 911 Targa', 'Porsche', '911 Targa', 1970, 'Amarelo', 96000, 200000.00, 200000.00, NULL, NULL, 1, '2026-07-01 17:26:38', 200000.00, 3),
(16, '2001 Mazda RX7 Type RB Feed Afflux Widebody', 'Mazda', 'RX-7 FD', 1995, 'Azul', 89000, 950000.00, 950000.00, NULL, NULL, 1, '2026-07-01 17:35:12', 950000.00, 3),
(17, 'Fusca', 'Volkswagen', 'Fusca 1600', 1976, 'Verde', 125000, 13000.00, 13000.00, NULL, NULL, 1, '2026-07-01 20:30:31', 13000.00, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `contatos`
--

CREATE TABLE `contatos` (
  `id` int(11) NOT NULL,
  `carro_id` int(11) DEFAULT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefone` varchar(80) NOT NULL,
  `mensagem` text NOT NULL,
  `resposta` text DEFAULT NULL,
  `respondido_em` timestamp NULL DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `contatos`
--

INSERT INTO `contatos` (`id`, `carro_id`, `nome`, `email`, `telefone`, `mensagem`, `resposta`, `respondido_em`, `criado_em`) VALUES
(1, 8, 'HENRY', '2@GMAIL.COM', '1988769784', '3R4FGRGERGVE', NULL, NULL, '2026-07-01 03:20:43'),
(2, 9, 'hb', 'bbrtbrb5b52@gmail.com', 'b5t4ggt4g4g', 't4g4g4', NULL, NULL, '2026-07-01 03:43:24'),
(3, 9, '5ythth4th', '2@gmail.com', '223435525426', 'ngngbt', 'oi oa noite', '2026-07-01 04:15:40', '2026-07-01 04:12:36'),
(4, NULL, '', '', '', 'oi', NULL, NULL, '2026-07-01 04:24:50'),
(5, 9, '', '', '', 'oii', NULL, NULL, '2026-07-01 04:24:59'),
(6, 10, 'wanderson', 'eu@gmail.com', '2134523245', '12.000', NULL, NULL, '2026-07-01 21:12:50'),
(7, 16, 'Cliente', '4@gmail.com', 'vr33', '4', NULL, NULL, '2026-07-01 21:49:02'),
(8, 15, 'edec', 'de2ec2ed@gmail.com', 'wdc33\'', 'fvtrvtr', NULL, NULL, '2026-07-01 21:52:35'),
(9, 14, 'rfrrrfr', 'yy@gmail.com', '22423513', 'vfvfgv', NULL, NULL, '2026-07-01 21:55:29');

-- --------------------------------------------------------

--
-- Estrutura para tabela `contato_mensagens`
--

CREATE TABLE `contato_mensagens` (
  `id` int(11) NOT NULL,
  `contato_id` int(11) NOT NULL,
  `sender` varchar(20) NOT NULL,
  `texto` text NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `contato_mensagens`
--

INSERT INTO `contato_mensagens` (`id`, `contato_id`, `sender`, `texto`, `criado_em`) VALUES
(1, 3, 'client', 'ngngbt', '2026-07-01 04:12:36'),
(2, 3, 'admin', 'oi oa noite', '2026-07-01 04:15:40'),
(3, 4, 'client', 'oi', '2026-07-01 04:24:50'),
(4, 5, 'client', 'oii', '2026-07-01 04:24:59'),
(5, 6, 'client', '12.000', '2026-07-01 21:12:50'),
(6, 7, 'client', '4', '2026-07-01 21:49:02'),
(7, 8, 'client', 'fvtrvtr', '2026-07-01 21:52:35'),
(8, 9, 'client', 'vfvfgv', '2026-07-01 21:55:29');

-- --------------------------------------------------------

--
-- Estrutura para tabela `entradas`
--

CREATE TABLE `entradas` (
  `id` int(11) NOT NULL,
  `carro_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `preco_custo` decimal(10,2) NOT NULL,
  `fornecedor` varchar(200) DEFAULT NULL,
  `data_entrada` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','cliente') DEFAULT 'cliente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `criado_em`) VALUES
(1, 'eee', 'u@gmail.com', '$2y$10$y0eHO6dkNP7jjFaTiWe4hOQ2Rg.7KEOSu2q6H2Zo7XmKMyWkwgt5S', 'cliente', '2026-06-30 22:39:00'),
(2, 'euu', 'a@gmail.com', '$2y$10$d2vsHEjuu4TO/R321LKIU.GSZgxKUGACMe6clOl/Oj0OBRRhat0a2', 'cliente', '2026-07-01 00:44:34'),
(3, 'Administrador', 'adm@gmail.com', '$2y$10$KazwFzyzYsG1Y9UTJjqJOeizjATo3GzNv4z8Kq4GwBGFxo4aMd9ui', 'admin', '2026-07-01 02:07:00'),
(5, 'Wanderson ', 'eu@gmail.com', '$2y$10$zpUTOLEQallPCXs4ECWc7.bcLPF.ZOcXh6tSE.MMIn8Cm02G.RBbC', 'cliente', '2026-07-01 21:12:02'),
(6, 'adm', 'uo@gmail.com', '$2y$10$xBRF0avXtFFpgFNKQ6aTkeuzcbz6awS/UXQ3HfHPllB5DVAHmn8c2', 'admin', '2026-07-01 23:23:48'),
(7, 'eu', 'uadm@gmail.com', '$2y$10$lvIMwggv9uecJNRVKDEP8e8frRKfarTVvhTMNMo57yDcwYGD9MlHe', 'admin', '2026-07-01 23:24:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` int(11) NOT NULL,
  `carro_id` int(11) NOT NULL,
  `cliente_nome` varchar(150) NOT NULL,
  `cliente_email` varchar(150) DEFAULT NULL,
  `cliente_telefone` varchar(50) DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_venda` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `whatsapp_config`
--

CREATE TABLE `whatsapp_config` (
  `id` int(11) NOT NULL,
  `numero` varchar(50) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `whatsapp_config`
--

INSERT INTO `whatsapp_config` (`id`, `numero`, `criado_em`, `atualizado_em`) VALUES
(1, '21982508679', '2026-07-01 22:29:21', '2026-07-01 22:41:01');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carros`
--
ALTER TABLE `carros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_carros_admin` (`admin_id`);

--
-- Índices de tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `contato_mensagens`
--
ALTER TABLE `contato_mensagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contato_id` (`contato_id`);

--
-- Índices de tabela `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carro_id` (`carro_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carro_id` (`carro_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `whatsapp_config`
--
ALTER TABLE `whatsapp_config`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carros`
--
ALTER TABLE `carros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `contato_mensagens`
--
ALTER TABLE `contato_mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `whatsapp_config`
--
ALTER TABLE `whatsapp_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carros`
--
ALTER TABLE `carros`
  ADD CONSTRAINT `fk_carros_admin` FOREIGN KEY (`admin_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`carro_id`) REFERENCES `carros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `entradas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `vendas`
--
ALTER TABLE `vendas`
  ADD CONSTRAINT `vendas_ibfk_1` FOREIGN KEY (`carro_id`) REFERENCES `carros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
