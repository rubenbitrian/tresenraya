-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 10-03-2022 a las 19:48:45
-- Versión del servidor: 8.0.27
-- Versión de PHP: 8.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+02:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tresenraya`
--
CREATE DATABASE IF NOT EXISTS `tresenraya` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tresenraya`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ficha`
--

DROP TABLE IF EXISTS `ficha`;
CREATE TABLE IF NOT EXISTS `ficha` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo_ficha_id` int DEFAULT NULL,
  `tablero_id` int DEFAULT NULL,
  `jugador_id` int DEFAULT NULL,
  `pos_fila` int NOT NULL,
  `pos_columna` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_ficha_tablero` (`tablero_id`,`pos_fila`,`pos_columna`),
  KEY `IDX_4B7E086186F5E896` (`tipo_ficha_id`),
  KEY `IDX_4B7E0861A8868F15` (`tablero_id`),
  KEY `IDX_4B7E0861B8A54D43` (`jugador_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugador`
--

DROP TABLE IF EXISTS `jugador`;
CREATE TABLE IF NOT EXISTS `jugador` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellidos` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuario` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_527D6F182265B05D` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida`
--

DROP TABLE IF EXISTS `partida`;
CREATE TABLE IF NOT EXISTS `partida` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ganador_id` int DEFAULT NULL,
  `jugador_1_id` int DEFAULT NULL,
  `jugador_2_id` int DEFAULT NULL,
  `tablero_id` int DEFAULT NULL,
  `inicio` datetime NOT NULL,
  `fin` datetime DEFAULT NULL,
  `en_curso` tinyint(1) NOT NULL,
  `finalizada` tinyint(1) NOT NULL,
  `empate` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A9C1580CA8868F15` (`tablero_id`),
  KEY `IDX_A9C1580CA338CEA5` (`ganador_id`),
  KEY `IDX_A9C1580C4A87D322` (`jugador_1_id`),
  KEY `IDX_A9C1580C58327CCC` (`jugador_2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tablero`
--

DROP TABLE IF EXISTS `tablero`;
CREATE TABLE IF NOT EXISTS `tablero` (
  `id` int NOT NULL AUTO_INCREMENT,
  `num_filas` int NOT NULL,
  `num_columnas` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_ficha`
--

DROP TABLE IF EXISTS `tipo_ficha`;
CREATE TABLE IF NOT EXISTS `tipo_ficha` (
  `id` int NOT NULL AUTO_INCREMENT,
  `simbolo` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4DC292A9C8541351` (`simbolo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

DROP TABLE IF EXISTS `turno`;
CREATE TABLE IF NOT EXISTS `turno` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partida_id` int DEFAULT NULL,
  `jugador_id` int DEFAULT NULL,
  `ficha_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E79767625030B25F` (`ficha_id`),
  KEY `IDX_E7976762F15A1987` (`partida_id`),
  KEY `IDX_E7976762B8A54D43` (`jugador_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ficha`
--
ALTER TABLE `ficha`
  ADD CONSTRAINT `FK_4B7E086186F5E896` FOREIGN KEY (`tipo_ficha_id`) REFERENCES `tipo_ficha` (`id`),
  ADD CONSTRAINT `FK_4B7E0861A8868F15` FOREIGN KEY (`tablero_id`) REFERENCES `tablero` (`id`),
  ADD CONSTRAINT `FK_4B7E0861B8A54D43` FOREIGN KEY (`jugador_id`) REFERENCES `jugador` (`id`);

--
-- Filtros para la tabla `partida`
--
ALTER TABLE `partida`
  ADD CONSTRAINT `FK_A9C1580C4A87D322` FOREIGN KEY (`jugador_1_id`) REFERENCES `jugador` (`id`),
  ADD CONSTRAINT `FK_A9C1580C58327CCC` FOREIGN KEY (`jugador_2_id`) REFERENCES `jugador` (`id`),
  ADD CONSTRAINT `FK_A9C1580CA338CEA5` FOREIGN KEY (`ganador_id`) REFERENCES `jugador` (`id`),
  ADD CONSTRAINT `FK_A9C1580CA8868F15` FOREIGN KEY (`tablero_id`) REFERENCES `tablero` (`id`);

--
-- Filtros para la tabla `turno`
--
ALTER TABLE `turno`
  ADD CONSTRAINT `FK_E79767625030B25F` FOREIGN KEY (`ficha_id`) REFERENCES `ficha` (`id`),
  ADD CONSTRAINT `FK_E7976762B8A54D43` FOREIGN KEY (`jugador_id`) REFERENCES `jugador` (`id`),
  ADD CONSTRAINT `FK_E7976762F15A1987` FOREIGN KEY (`partida_id`) REFERENCES `partida` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
