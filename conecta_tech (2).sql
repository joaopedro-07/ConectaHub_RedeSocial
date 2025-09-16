-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Set-2025 às 21:16
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `conecta_tech`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `mensagens`
--

INSERT INTO `mensagens` (`id`, `usuario`, `texto`, `data_envio`) VALUES
(1, 'mamute', 'ola isso é um teste de mensagem', '2025-09-16 17:02:16'),
(2, 'mamute', 'olá', '2025-09-16 17:02:51'),
(3, 'mamute', 'oi joaoo', '2025-09-16 17:04:54'),
(4, 'mamute', 'oii', '2025-09-16 17:16:35');

-- --------------------------------------------------------

--
-- Estrutura da tabela `posts`
--

CREATE TABLE `posts` (
  `id_post` int(20) NOT NULL,
  `titulo_post` varchar(155) NOT NULL,
  `descricao_post` varchar(155) NOT NULL,
  `data_post` date NOT NULL,
  `fk_usuario_post` int(155) NOT NULL,
  `imagem_post` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(20) NOT NULL,
  `nome_usuario` varchar(155) NOT NULL,
  `email_usuario` varchar(155) NOT NULL,
  `senha_usuario` varchar(155) NOT NULL,
  `foto_usuario` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome_usuario`, `email_usuario`, `senha_usuario`, `foto_usuario`) VALUES
(5, 'mamute', 'mamute@gmail.com', '$2y$10$Maun4p5FvsYEpfMHOMd/lu.XJ3jLiIJrA7TdrZ1UGkGqqS.2Bala.', ''),
(8, 'gbr', 'gbr@gmail.com', '$2y$10$zdgv1CVtvtriHoxDl0iAveI90BydGwMnoVH1yAyjeUifTIzp1CpaG', 0x363863396161353633643065662e504e47),
(9, 'João Pedro', 'jotapepe.machado@gmail.com', '$2y$10$Lqwt4RMf1ayiNfqnpLIUAuxpB1JLHtoczI2cMOKOcglJyyzAlqaBa', 0x363863396237353632636439352e706e67);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `fk_usuario_post` (`fk_usuario_post`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_usuario_post` FOREIGN KEY (`fk_usuario_post`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
