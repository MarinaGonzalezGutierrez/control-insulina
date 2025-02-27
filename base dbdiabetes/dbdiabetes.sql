-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-02-2025 a las 00:39:32
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbdiabetes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comidas`
--

CREATE TABLE `comidas` (
  `idUsuario` int(255) NOT NULL,
  `fecha` date NOT NULL,
  `glucosa_pre` int(11) DEFAULT NULL,
  `glucosa_post` int(11) DEFAULT NULL,
  `racion` int(11) DEFAULT NULL,
  `insulina` int(11) DEFAULT NULL,
  `tipoComida` enum('Desayuno','Comida','Cena','Aperitivo','Merienda') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comidas`
--

INSERT INTO `comidas` (`idUsuario`, `fecha`, `glucosa_pre`, `glucosa_post`, `racion`, `insulina`, `tipoComida`) VALUES
(2, '2025-02-27', 1, 1, 1, 1, 'Desayuno'),
(2, '2025-02-27', 0, 1, 1, 1, 'Aperitivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `controlglucosa`
--

CREATE TABLE `controlglucosa` (
  `idUsuario` int(255) NOT NULL,
  `fecha` date NOT NULL,
  `lenta` tinyint(1) DEFAULT NULL,
  `deporte` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `controlglucosa`
--

INSERT INTO `controlglucosa` (`idUsuario`, `fecha`, `lenta`, `deporte`) VALUES
(1, '2025-02-27', 7, 3),
(2, '2025-02-27', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hiper`
--

CREATE TABLE `hiper` (
  `idUsuario` int(255) NOT NULL,
  `fecha` date NOT NULL,
  `tipoComida` enum('Desayuno','Comida','Cena','Aperitivo','Merienda') NOT NULL,
  `hora` time DEFAULT NULL,
  `glucosa` int(11) DEFAULT NULL,
  `correccion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hipo`
--

CREATE TABLE `hipo` (
  `idUsuario` int(255) NOT NULL,
  `fecha` date NOT NULL,
  `tipoComida` enum('Desayuno','Comida','Cena','Aperitivo','Merienda') NOT NULL,
  `hora` time DEFAULT NULL,
  `glucosa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(255) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `fechaNac` date DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `nombre`, `apellido`, `fechaNac`, `username`, `password`) VALUES
(1, 'Marina', 'Gonz', '2025-02-27', 'marinagogu', '$2y$10$IQxUYZfoedgJnEvtg.T1feL7HPoGPOE.48u7U2p1C5Ig6sePk7nAG'),
(2, '1', '1', '2025-01-30', '1', '$2y$10$H2JGcRKrU/1VeAt/oETxn.vM.08pXMDj2dsayhtSNFElyoZEnA8Xe');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comidas`
--
ALTER TABLE `comidas`
  ADD PRIMARY KEY (`idUsuario`,`fecha`,`tipoComida`);

--
-- Indices de la tabla `controlglucosa`
--
ALTER TABLE `controlglucosa`
  ADD PRIMARY KEY (`idUsuario`,`fecha`);

--
-- Indices de la tabla `hiper`
--
ALTER TABLE `hiper`
  ADD PRIMARY KEY (`idUsuario`,`fecha`,`tipoComida`);

--
-- Indices de la tabla `hipo`
--
ALTER TABLE `hipo`
  ADD PRIMARY KEY (`idUsuario`,`fecha`,`tipoComida`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comidas`
--
ALTER TABLE `comidas`
  ADD CONSTRAINT `comidas_ibfk_1` FOREIGN KEY (`idUsuario`,`fecha`) REFERENCES `controlglucosa` (`idUsuario`, `fecha`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `controlglucosa`
--
ALTER TABLE `controlglucosa`
  ADD CONSTRAINT `controlglucosa_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `hiper`
--
ALTER TABLE `hiper`
  ADD CONSTRAINT `hiper_ibfk_1` FOREIGN KEY (`idUsuario`,`fecha`,`tipoComida`) REFERENCES `comidas` (`idUsuario`, `fecha`, `tipoComida`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `hipo`
--
ALTER TABLE `hipo`
  ADD CONSTRAINT `hipo_ibfk_1` FOREIGN KEY (`idUsuario`,`fecha`,`tipoComida`) REFERENCES `comidas` (`idUsuario`, `fecha`, `tipoComida`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
