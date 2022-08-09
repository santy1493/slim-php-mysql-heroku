-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2022 at 10:42 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `comanda_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `fecha_alta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `sector`, `usuario`, `clave`, `estado`, `fecha_alta`) VALUES
(1, 'santi', 'admin', 'santi19', 'santi19', 1, '2017-08-14'),
(2, 'mariana', 'mozos', 'mariana10', 'mariana10', 1, '2022-08-08'),
(3, 'carlos', 'cocineros', 'carlos10', 'carlos10', 1, '2022-08-08'),
(4, 'pedro', 'cerveceros', 'pedro10', 'pedro10', 1, '2012-04-18'),
(5, 'juan', 'bartender', 'juan10', 'juan10', 1, '2012-04-18');

-- --------------------------------------------------------

--
-- Table structure for table `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(11) NOT NULL,
  `cod_pedido` varchar(10) NOT NULL,
  `mesa` decimal(10,2) DEFAULT NULL,
  `restaurante` decimal(10,2) DEFAULT NULL,
  `mozo` decimal(10,2) DEFAULT NULL,
  `cocinero` decimal(10,2) DEFAULT NULL,
  `puntaje_total` decimal(10,2) NOT NULL,
  `comentario` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `encuestas`
--

INSERT INTO `encuestas` (`id`, `cod_pedido`, `mesa`, `restaurante`, `mozo`, `cocinero`, `puntaje_total`, `comentario`) VALUES
(1, 'i521r', '9.60', '4.50', '7.20', '6.10', '6.85', 'Primer comentario'),
(2, 'fmb32', '5.60', '6.20', '7.20', '4.10', '5.78', 'Otro comentario.........');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `tiempo_estimado` int(11) NOT NULL,
  `hora_inicio` datetime DEFAULT NULL,
  `hora_terminado` datetime DEFAULT NULL,
  `id_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `id_producto`, `cantidad`, `estado`, `tiempo_estimado`, `hora_inicio`, `hora_terminado`, `id_pedido`) VALUES
(21, 1, 1, 'pendiente', 0, NULL, NULL, 19),
(22, 2, 2, 'pendiente', 0, NULL, NULL, 19),
(23, 3, 1, 'pendiente', 0, NULL, NULL, 19),
(24, 4, 1, 'en preparacion', 10, '2022-08-09 19:00:12', NULL, 19),
(25, 1, 1, 'listo para servir', 12, '2022-08-09 21:58:49', '2022-08-09 22:26:28', 20),
(26, 2, 2, 'listo para servir', 12, '2022-08-09 22:23:15', '2022-08-09 22:26:45', 20),
(27, 3, 1, 'listo para servir', 12, '2022-08-09 22:23:07', '2022-08-09 22:24:32', 20),
(28, 4, 1, 'listo para servir', 12, '2022-08-09 22:21:39', '2022-08-09 22:25:23', 20);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `path` varchar(100) NOT NULL,
  `method` varchar(20) NOT NULL,
  `fecha_alta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `empleado_id`, `sector`, `path`, `method`, `fecha_alta`) VALUES
(44, 3, 'cocineros', '/pedidos', 'GET', '2022-08-09'),
(45, 3, 'cocineros', '/pedidos', 'GET', '2022-08-09'),
(46, 2, 'mozos', '/pedidos', 'POST', '2022-08-09'),
(47, 3, 'cocineros', '/pedidos', 'GET', '2022-08-09'),
(48, 4, 'cerveceros', '/pedidos', 'POST', '2022-08-09'),
(49, 2, 'mozos', '/pedidos', 'POST', '2022-08-09'),
(50, 2, 'mozos', '/pedidos', 'POST', '2022-08-09'),
(51, 2, 'mozos', '/pedidos', 'GET', '2022-08-09'),
(52, 2, 'mozos', '/pedidos', 'GET', '2022-08-09'),
(53, 2, 'mozos', '/pedidos', 'GET', '2022-08-09'),
(54, 2, 'mozos', '/pedidos', 'GET', '2022-08-09'),
(55, 2, 'mozos', '/pedidos', 'GET', '2022-08-09'),
(56, 2, 'mozos', '/pedidos', 'GET', '2022-08-09');

-- --------------------------------------------------------

--
-- Table structure for table `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `contador_pedidos` int(11) NOT NULL,
  `montos_acumulados` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mesas`
--

INSERT INTO `mesas` (`id`, `estado`, `foto`, `id_pedido`, `contador_pedidos`, `montos_acumulados`) VALUES
(1, 'abierta', '0', 0, 0, '0.00'),
(2, 'cerrada', NULL, 0, 4, '8229.60'),
(3, 'cerrada', NULL, 0, 2, '5486.40');

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `fecha_hora` date NOT NULL,
  `precio_total` decimal(10,2) NOT NULL,
  `cod_alfanumerico` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id`, `estado`, `fecha_hora`, `precio_total`, `cod_alfanumerico`) VALUES
(19, 'en preparacion', '2022-08-09', '2743.20', 'i521r'),
(20, 'listo para servir', '2022-08-09', '2743.20', 'fmb32');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `sector` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `descripcion`, `precio`, `sector`) VALUES
(1, 'milanesa a caballo', '690.00', 'cocineros'),
(2, 'hamburguesa de garbanzo', '540.00', 'cocineros'),
(3, 'corona', '520.30', 'cerveceros'),
(4, 'daikiri', '452.90', 'bartender');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
