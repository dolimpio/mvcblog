-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 30-10-2022 a las 20:29:17
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mvcblog`
--
CREATE DATABASE IF NOT EXISTS `mvcblog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mvcblog`;
--
-- anadiendo usuario para la conexion del php
--
create user 'mvcuser'@'localhost' identified by 'mvcpass';
grant all privileges on mvcblog.* to 'mvcuser'@'localhost' WITH GRANT OPTION;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `typeDB` enum('combustible','alimentacion','comunicaciones','suministros','ocio') DEFAULT NULL,
  `dateDB` date DEFAULT NULL,
  `quantityDB` varchar(255) DEFAULT NULL,
  `descriptionDB` varchar(255) DEFAULT NULL,
  `fileDB` varchar(255) DEFAULT NULL,
  `ownerDB` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `uuid` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `expenses`
--

INSERT INTO `expenses` (`id`, `typeDB`, `dateDB`, `quantityDB`, `descriptionDB`, `fileDB`, `ownerDB`) VALUES
(1, 'combustible', '2022-10-29', '3', 'Se han comprado 3 combustibles', '', 'user1'),
(2, 'ocio', '2022-10-29', '2', 'asdasd', '', 'registro'),
(3, 'alimentacion', '2022-10-29', '4', 'comida comida comida comida', '', 'registro'),
(4, 'combustible', '2022-10-27', '555', 'viene con foto', '9b228d6d278df4e00f458d80cf841b371635205594_main.png', 'user1'),
(5, 'ocio', '2022-10-24', '3', 'NUMERO 1', '', 'user1'),
(6, 'combustible', '2022-10-25', '5', 'NUMERO 2', '', 'user1'),
(7, 'suministros', '2022-10-26', '66', 'NUMERO 3', '', 'user1'),
(8, 'combustible', '2022-10-29', '33', 'descripcion sin mas', '9b228d6d278df4e00f458d80cf841b371635205594_main.png', 'user1'),
(9, 'ocio', '2022-10-29', '55', 'descripcion con mas', '9b228d6d278df4e00f458d80cf841b371635205594_main.png', 'user1'),
(10, 'alimentacion', '2022-10-29', '444', 'se ha subido la imagen 3 veces', '9b228d6d278df4e00f458d80cf841b371635205594_main.png', 'user1'),
(12, 'ocio', '2022-10-29', '44', '232', '', 'user1'),
(13, 'comunicaciones', '2022-10-01', '6666666', 'gggggggggggg                        ', '', 'user1'),
(14, 'ocio', '2022-10-30', '4564564', 'Han pasado 7 meses...', '', 'user1'),
(15, 'ocio', '2022-10-13', '999', 'Una descripcion, por ejemplo.', '', 'user1'),
(16, 'suministros', '2022-10-30', '10', 'Basta ya.', '', 'user1'),
(17, 'suministros', '2022-10-13', '7777', 'Mesas y sillas', '', 'user1'),
(18, 'suministros', '2022-10-30', '420420420', 'Natillas... Natillas... Natillas... ojala tener natillas infinitas', '', 'user1'),
(19, 'comunicaciones', '2022-10-30', '76767676', '', '', 'user1'),
(20, 'ocio', '2022-10-30', '666666666666', 'Basta ya v2.0', 'imagen.png', 'user1'),
(21, 'combustible', '2022-10-30', '444444444', 'Ultima descripcion', 'imagen.png', 'user1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `username` varchar(255) NOT NULL,
  `passwd` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `lastlogindate` DATE NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`username`, `passwd`, `email`) VALUES
('holahola', 'holahola', 'hola@hola.hola'),
('user1', 'user1', 'user1@user1.com'),
('registro', 'registro', 'registro@gmial.ccas');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ownerDB` (`ownerDB`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`ownerDB`) REFERENCES `users` (`username`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
