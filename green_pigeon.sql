-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.1.34-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win32
-- HeidiSQL Versão:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para green_pigeon
CREATE DATABASE IF NOT EXISTS `green_pigeon` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `green_pigeon`;

-- Copiando estrutura para tabela green_pigeon.categoria
CREATE TABLE IF NOT EXISTS `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela green_pigeon.categoria: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
REPLACE INTO `categoria` (`id`, `nome`, `link`) VALUES
	(1, 'Metal', 'metal'),
	(2, 'Papel', 'papel'),
	(4, 'a', 'uploads/icones-categoria/5b93d80c09e61.png');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;

-- Copiando estrutura para tabela green_pigeon.dica
CREATE TABLE IF NOT EXISTS `dica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(75) NOT NULL,
  `texto` text NOT NULL,
  `criador_id` int(11) NOT NULL,
  `data_criacao` date NOT NULL,
  `categoria_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `criador_id` (`criador_id`),
  CONSTRAINT `dica_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dica_ibfk_2` FOREIGN KEY (`criador_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela green_pigeon.dica: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `dica` DISABLE KEYS */;
REPLACE INTO `dica` (`id`, `titulo`, `texto`, `criador_id`, `data_criacao`, `categoria_id`) VALUES
	(1, 'Maneirasso', '<p><img src="https://conteudo.imguol.com.br/c/entretenimento/c4/2018/05/15/super-mario-odyssey-1526426783086_v2_1170x540.jpgx" class="responsive-img" style="width:423.641px;" alt="super-mario-odyssey-1526426783086_v2_1170x540.jpgx" /><br /></p>', 1, '2016-11-17', 1),
	(2, 'Sei lá', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In pulvinar bibendum aliquet. Nunc in dui sed tellus tempus tempus. Proin sagittis erat nec tortor sollicitudin, et accumsan urna fringilla. Sed tincidunt nunc quis libero dignissim, nec porta magna egestas. Proin consequat libero eget velit condimentum, et venenatis massa tempus. Sed non imperdiet orci. Duis ullamcorper risus erat, et elementum turpis blandit lacinia. Sed vel rutrum ligula. Aliquam eu massa tristique, dapibus massa in, mattis nibh. Etiam aliquam ante in accumsan facilisis. Donec sagittis eros sem, sit amet pharetra lacus porttitor ut. Morbi lobortis magna vitae vestibulum faucibus. Fusce ullamcorper ligula sed nisi vulputate congue. In bibendum lobortis velit, eu vestibulum risus. Morbi sed justo nulla.', 3, '2016-11-17', 2),
	(5, 'Um título legal', '<p><img src="https://conteudo.imguol.com.br/c/entretenimento/c4/2018/05/15/super-mario-odyssey-1526426783086_v2_1170x540.jpgx" class="responsive-img" style="width:423.641px;" alt="super-mario-odyssey-1526426783086_v2_1170x540.jpgx" /><br /></p>', 1, '2018-09-20', 1),
	(6, 'BEM DOIDO', '<p><img src="https://conteudo.imguol.com.br/c/entretenimento/c4/2018/05/15/super-mario-odyssey-1526426783086_v2_1170x540.jpgx" class="responsive-img" style="width:423.641px;" alt="super-mario-odyssey-1526426783086_v2_1170x540.jpgx" /><br /></p>', 3, '2016-11-17', 1),
	(7, 'SAFASF', '<p><span style="font-size:10px;">?</span><br /></p>', 1, '2018-09-20', 4);
/*!40000 ALTER TABLE `dica` ENABLE KEYS */;

-- Copiando estrutura para tabela green_pigeon.evento
CREATE TABLE IF NOT EXISTS `evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `texto` mediumtext NOT NULL,
  `criador_id` int(11) NOT NULL,
  `capa` varchar(100) NOT NULL,
  `data_criacao` date DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `data_evento` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_criador_id` (`criador_id`),
  CONSTRAINT `fk_criador_id` FOREIGN KEY (`criador_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela green_pigeon.evento: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `evento` DISABLE KEYS */;
REPLACE INTO `evento` (`id`, `titulo`, `texto`, `criador_id`, `capa`, `data_criacao`, `latitude`, `longitude`, `data_evento`) VALUES
	(1, 'Um evento maneiro', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ut ipsum at enim condimentum euismod. Phasellus luctus cursus elit at tempor. Etiam pharetra eget justo et egestas. Suspendisse potenti. Donec id sollicitudin velit, ut ullamcorper nisi. Nunc egestas placerat dui in iaculis. Duis sed velit id orci blandit commodo. Nam id enim ac enim tempus vulputate euismod laoreet leo. Pellentesque aliquam magna ante, at condimentum orci volutpat vel.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ut ipsum at enim condimentum euismod. Phasellus luctus cursus elit at tempor. Etiam pharetra eget justo et egestas. Suspendisse potenti. Donec id sollicitudin velit, ut ullamcorper nisi. Nunc egestas placerat dui in iaculis. Duis sed velit id orci blandit commodo. Nam id enim ac enim tempus vulputate euismod laoreet leo. Pellentesque aliquam magna ante, at condimentum orci volutpat vel.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ut ipsum at enim condimentum euismod. Phasellus luctus cursus elit at tempor. Etiam pharetra eget justo et egestas. Suspendisse potenti. Donec id sollicitudin velit, ut ullamcorper nisi. Nunc egestas placerat dui in iaculis. Duis sed velit id orci blandit commodo. Nam id enim ac enim tempus vulputate euismod laoreet leo. Pellentesque aliquam magna ante, at condimentum orci volutpat vel.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ut ipsum at enim condimentum euismod. Phasellus luctus cursus elit at tempor. Etiam pharetra eget justo et egestas. Suspendisse potenti. Donec id sollicitudin velit, ut ullamcorper nisi. Nunc egestas placerat dui in iaculis. Duis sed velit id orci blandit commodo. Nam id enim ac enim tempus vulputate euismod laoreet leo. Pellentesque aliquam magna ante, at condimentum orci volutpat vel.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ut ipsum at enim condimentum euismod. Phasellus luctus cursus elit at tempor. Etiam pharetra eget justo et egestas. Suspendisse potenti. Donec id sollicitudin velit, ut ullamcorper nisi. Nunc egestas placerat dui in iaculis. Duis sed velit id orci blandit commodo. Nam id enim ac enim tempus vulputate euismod laoreet leo. Pellentesque aliquam magna ante, at condimentum orci volutpat vel.', 1, 'uploads/avatar/5b9c5a0a3e06b.jpg', '2018-09-27', NULL, NULL, '2018-09-26'),
	(2, '2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in porttitor nulla, pretium accumsan arcu. Mauris turpis lectus, cursus quis turpis nec, dictum feugiat urna. Phasellus eget sagittis sem. Etiam facilisis quam nisi, id pellentesque eros malesuada non. Praesent tempor vehicula leo, mollis efficitur diam eleifend nec. In hac habitasse platea dictumst. Vestibulum posuere magna metus, eu ultricies tortor vestibulum et.', 3, 'uploads/avatar/5b9c5a0a3e06b.jpg', '2018-04-17', NULL, NULL, NULL),
	(3, 'EVENTO DA HORA', '<p style="text-align:center;"><span style="font-size:24px;">?AOOOOOA</span></p><p style="text-align:center;"></p><div style="text-align:justify;"><span style="font-size:12px;">?saudhiasufhsahfuiashf dsuhfiasufh oasifio shfh saoifjiosaj fioasiofj asiofh oisjifoasjio fjasiofj ioadjio jdaoigh dahogijad ioghoiajgio ajdio gjoadijg iadh gpiasdio afjo</span></div><br /><p style="text-align:center;"><span style="font-size:24px;">?AOOOOOA</span></p><p style="text-align:center;"></p><div style="text-align:justify;"><span style="font-size:12px;">?saudhiasufhsahfuiashf dsuhfiasufh oasifio shfh saoifjiosaj fioasiofj asiofh oisjifoasjio fjasiofj ioadjio jdaoigh dahogijad ioghoiajgio ajdio gjoadijg iadh gpiasdio afjo</span></div><br /><p style="text-align:center;"><span style="font-size:24px;">?AOOOOOA</span></p><p style="text-align:center;"></p><div style="text-align:justify;"><span style="font-size:12px;">?saudhiasufhsahfuiashf dsuhfiasufh oasifio shfh saoifjiosaj fioasiofj asiofh oisjifoasjio fjasiofj ioadjio jdaoigh dahogijad ioghoiajgio ajdio gjoadijg iadh gpiasdio afjo</span></div><br /><p style="text-align:center;"><span style="font-size:24px;">?AOOOOOA</span></p><p style="text-align:center;"></p><div style="text-align:justify;"><span style="font-size:12px;">?saudhiasufhsahfuiashf dsuhfiasufh oasifio shfh saoifjiosaj fioasiofj asiofh oisjifoasjio fjasiofj ioadjio jdaoigh dahogijad ioghoiajgio ajdio gjoadijg iadh gpiasdio afjo</span></div><br /><p style="text-align:center;"><span style="font-size:24px;">?AOOOOOA</span></p><p style="text-align:center;"></p><div style="text-align:justify;"><span style="font-size:12px;">?saudhiasufhsahfuiashf dsuhfiasufh oasifio shfh saoifjiosaj fioasiofj asiofh oisjifoasjio fjasiofj ioadjio jdaoigh dahogijad ioghoiajgio ajdio gjoadijg iadh gpiasdio afjo</span></div><br /><p style="text-align:center;"><span style="font-size:24px;">?AOOOOOA</span></p><p style="text-align:center;"></p><div style="text-align:justify;"><span style="font-size:12px;">?saudhiasufhsahfuiashf dsuhfiasufh oasifio shfh saoifjiosaj fioasiofj asiofh oisjifoasjio fjasiofj ioadjio jdaoigh dahogijad ioghoiajgio ajdio gjoadijg iadh gpiasdio afjo</span></div><br /><p style="text-align:center;"><span style="font-size:24px;">?AOOOOOA</span></p><p style="text-align:center;"></p><div style="text-align:justify;"><span style="font-size:12px;">?saudhiasufhsahfuiashf dsuhfiasufh oasifio shfh saoifjiosaj fioasiofj asiofh oisjifoasjio fjasiofj ioadjio jdaoigh dahogijad ioghoiajgio ajdio gjoadijg iadh gpiasdio afjo</span></div><br />', 1, 'uploads/capa_evento/5ba3a4a25c020.jpg', '2018-09-20', '-28.05074227606612', '-48.617401856815206', '2018-11-25'),
	(4, 'Sei lá meo', '<p>Sei lá, uma coleta de lixo <font color="#b71c1c"><u><b>maneira </b></u></font>ai e é isso ai </p><p>Sei lá, uma coleta de lixo <font color="#b71c1c"><u><b>maneira </b></u></font>ai e é isso ai </p><p>Sei lá, uma coleta de lixo <font color="#b71c1c"><u><b>maneira </b></u></font>ai e é isso ai </p><p>Sei lá, uma coleta de lixo <font color="#b71c1c"><u><b>maneira </b></u></font>ai e é isso ai </p><p>Sei lá, uma coleta de lixo <font color="#b71c1c"><u><b>maneira </b></u></font>ai e é isso ai </p><p>Sei lá, uma coleta de lixo <font color="#b71c1c"><u><b>maneira </b></u></font>ai e é isso ai </p>', 5, 'uploads/capa_evento/5ba8d3c4a3a9b.jpg', '2018-09-24', '-28.116299674294325', '-48.68584159740497', '2018-09-29');
/*!40000 ALTER TABLE `evento` ENABLE KEYS */;

-- Copiando estrutura para tabela green_pigeon.ponto_coleta
CREATE TABLE IF NOT EXISTS `ponto_coleta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(75) NOT NULL DEFAULT 'Ponto de coleta',
  `latitude` varchar(15) DEFAULT NULL,
  `longitude` varchar(15) DEFAULT NULL,
  `descricao` text,
  `exibir` tinyint(1) DEFAULT NULL,
  `data_criacao` date DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `ponto_coleta_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ponto_coleta_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela green_pigeon.ponto_coleta: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `ponto_coleta` DISABLE KEYS */;
REPLACE INTO `ponto_coleta` (`id`, `nome`, `latitude`, `longitude`, `descricao`, `exibir`, `data_criacao`, `usuario_id`, `categoria_id`) VALUES
	(1, 'Imbituba', '-28.2405', '-48.6703', 'Coordenadas geográficas de Imbituba, Brasil em sistema de coordenadas WGS 84 que é um padrão em cartografia, geodésia e navegação, incluindo Sistema', 1, '2018-09-07', 1, 2),
	(4, 'Minha casa', '-28.24722066814', '-48.70220971972', NULL, NULL, '2018-09-15', 1, 4);
/*!40000 ALTER TABLE `ponto_coleta` ENABLE KEYS */;

-- Copiando estrutura para tabela green_pigeon.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `senha` char(32) NOT NULL,
  `nome` varchar(75) DEFAULT NULL,
  `avatar` varchar(75) DEFAULT 'template/default/images/user.png',
  `adm` int(11) DEFAULT NULL,
  `pontuacao` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela green_pigeon.usuario: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
REPLACE INTO `usuario` (`id`, `email`, `senha`, `nome`, `avatar`, `adm`, `pontuacao`) VALUES
	(1, 'leo@gmail.com', '9f6d80225f2446d6ea0ef4392ce7c8ce', 'Leonardson', 'uploads/avatar/5b9c5a0a3e06b.jpg', 2, 10000),
	(3, 'arnaldo@email.com', '9f6d80225f2446d6ea0ef4392ce7c8ce', 'Arnaldo', 'template/default/images/user.png', 1, 0),
	(4, 'aa', '22ea9d2ba84787dfb2c916e6959122be', 'aa', 'template/default/images/user.png', 1, 0),
	(5, 'a', '22ea9d2ba84787dfb2c916e6959122be', 'a', 'template/default/images/user.png', 1, 0);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;

-- Copiando estrutura para tabela green_pigeon.voto
CREATE TABLE IF NOT EXISTS `voto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL DEFAULT '0',
  `ponto_id` int(11) NOT NULL DEFAULT '0',
  `valor` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_voto_usuario` (`usuario_id`),
  KEY `FK_voto_ponto_coleta` (`ponto_id`),
  CONSTRAINT `FK_voto_ponto_coleta` FOREIGN KEY (`ponto_id`) REFERENCES `ponto_coleta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_voto_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela green_pigeon.voto: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `voto` DISABLE KEYS */;
/*!40000 ALTER TABLE `voto` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
