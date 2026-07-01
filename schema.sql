-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/07/2026 às 06:22
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
(9, 'Fiat Uno', 'Fiat', 'Com Escada', 2019, 'Branco', 80, 16.00, 16000.00, NULL, NULL, 1, '2026-07-01 03:02:08', 16000.00, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `carro_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `valor_final` decimal(10,2) DEFAULT NULL,
  `data_compra` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 9, '5ythth4th', '2@gmail.com', '223435525426', 'ngngbt', 'oi oa noite', '2026-07-01 04:15:40', '2026-07-01 04:12:36');

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
(2, 3, 'admin', 'oi oa noite', '2026-07-01 04:15:40');

-- --------------------------------------------------------

--
-- Estrutura para tabela `lances`
--

CREATE TABLE `lances` (
  `id` int(11) NOT NULL,
  `carro_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_lance` timestamp NOT NULL DEFAULT current_timestamp()
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
(3, 'Administrador', 'adm@gmail.com', '$2y$10$KazwFzyzYsG1Y9UTJjqJOeizjATo3GzNv4z8Kq4GwBGFxo4aMd9ui', 'admin', '2026-07-01 02:07:00');

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
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carro_id` (`carro_id`),
  ADD KEY `usuario_id` (`usuario_id`);

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
-- Índices de tabela `lances`
--
ALTER TABLE `lances`
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
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carros`
--
ALTER TABLE `carros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `contato_mensagens`
--
ALTER TABLE `contato_mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `lances`
--
ALTER TABLE `lances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carros`
--
ALTER TABLE `carros`
  ADD CONSTRAINT `fk_carros_admin` FOREIGN KEY (`admin_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`carro_id`) REFERENCES `carros` (`id`),
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `lances`
--
ALTER TABLE `lances`
  ADD CONSTRAINT `lances_ibfk_1` FOREIGN KEY (`carro_id`) REFERENCES `carros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lances_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;