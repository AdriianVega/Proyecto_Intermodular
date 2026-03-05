-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-03-2026 a las 13:23:41
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `noticias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rol` char(1) NOT NULL,
  `icono` varchar(255) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id`, `email`, `password`, `nombre`, `rol`, `icono`, `create_time`) VALUES
(1, 'aadriianvega@gmail.com', '$2y$10$PLPo1LooPX3Lah9CcDfjqepR1SEpSm.YtWEmWF8/pENZhsIhAWK16 ', 'Adrián Nataniel', '1', 'adriannataniel.jpg', '2026-03-03 10:14:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`) VALUES
(1, 'Cultura');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medio`
--

CREATE TABLE `medio` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `medio`
--

INSERT INTO `medio` (`id`, `nombre`, `url`) VALUES
(1, 'ElPais', 'https://elpais.com/');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticia`
--

CREATE TABLE `noticia` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `texto_original` text DEFAULT NULL,
  `texto_traducido` text DEFAULT NULL,
  `fecha_publicacion` date DEFAULT NULL,
  `pais_id` int(11) DEFAULT NULL,
  `medio_id` int(11) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `noticia`
--

INSERT INTO `noticia` (`id`, `nombre`, `url`, `texto_original`, `texto_traducido`, `fecha_publicacion`, `pais_id`, `medio_id`, `create_time`) VALUES
(1, 'Una vivienda queda reducida a cenizas tras un devastador incendio nocturno', 'f', 'Un violento incendio declarado en la madrugada del lunes ha destruido por completo una vivienda unifamiliar\r\n                        situada en las afueras de Brno, en la República Checa. Las llamas comenzaron alrededor de las 2:17 de la\r\n                        madrugada, según confirmó el cuerpo regional de bomberos. Vecinos de la zona alertaron a los servicios de\r\n                        emergencia tras escuchar una fuerte explosión proveniente del interior de la vivienda. En cuestión de minutos,\r\n                        el fuego se extendió por la estructura de madera, dificultando las labores iniciales de contención. Al lugar\r\n                        acudieron cuatro dotaciones de bomberos que trabajaron durante más de tres horas para sofocar el incendio. Las\r\n                        condiciones meteorológicas, con temperaturas bajo cero y viento moderado, complicaron la intervención. La\r\n                        vivienda, construida hace más de 40 años, quedó completamente calcinada. El tejado colapsó poco después de la\r\n                        llegada de los equipos de emergencia. Afortunadamente, los ocupantes lograron evacuar el inmueble a tiempo tras\r\n                        percibir olor a humo en la planta baja. Ninguno de los miembros de la familia sufrió heridas graves. Las\r\n                        autoridades locales han acordonado la zona mientras se realizan las primeras evaluaciones estructurales y se\r\n                        asegura el perímetro. \r\n\r\nSegún fuentes oficiales, el incendio podría haberse originado en el sistema eléctrico del inmueble. Los\r\n                        investigadores no descartan un fallo en una instalación antigua que no había sido renovada recientemente.\r\n                        Especialistas en incendios han comenzado el análisis de los restos para determinar el punto exacto de ignición.\r\n                        El informe preliminar estará disponible en los próximos días. El alcalde de la localidad expresó su preocupación\r\n                        por el estado de muchas viviendas antiguas en la región, señalando la necesidad de revisar instalaciones\r\n                        eléctricas obsoletas. Vecinos cercanos afirmaron haber visto chispas en la parte trasera de la casa minutos\r\n                        antes de que las llamas fueran visibles desde la calle. Las autoridades han recordado la importancia de contar\r\n                        con detectores de humo funcionales, especialmente durante el invierno, cuando aumenta el uso de sistemas de\r\n                        calefacción. Por el momento, no se han encontrado indicios que apunten a un origen intencional del siniestro.\r\n\r\nLa familia afectada, compuesta por cuatro miembros, fue trasladada temporalmente a casa de unos familiares\r\n                        mientras se gestionan soluciones habitacionales de emergencia. Servicios sociales del municipio han activado un\r\n                        protocolo de asistencia para proporcionar apoyo económico inicial y cobertura básica. Organizaciones vecinales\r\n                        han iniciado una campaña solidaria para recaudar fondos y donar ropa, alimentos y productos esenciales. El\r\n                        impacto emocional ha sido significativo, especialmente para los dos menores que residían en la vivienda.\r\n                        Psicólogos municipales han ofrecido asistencia voluntaria para ayudar a la familia a afrontar la pérdida\r\n                        material y el trauma del suceso. Se estima que los daños materiales superan los 250.000 euros, aunque la cifra\r\n                        podría aumentar tras la evaluación completa.Expertos en seguridad advierten que los incendios domésticos aumentan durante los meses de invierno en Europa\r\n                        Central debido al mayor uso de calefacción eléctrica y sistemas de combustión. En la República Checa, los\r\n                        servicios de emergencia registraron el pasado año más de 15.000 intervenciones relacionadas con incendios\r\n                        estructurales. Las autoridades recomiendan realizar revisiones periódicas de instalaciones eléctricas y calderas\r\n                        para reducir riesgos. También se aconseja no sobrecargar enchufes múltiples y desconectar dispositivos\r\n                        eléctricos durante la noche cuando no estén en uso. El cuerpo de bomberos ha anunciado que intensificará\r\n                        campañas de concienciación ciudadana en las próximas semanas. Mientras continúan las investigaciones, la\r\n                        comunidad permanece conmocionada por un suceso que ha vuelto a poner sobre la mesa la importancia de la\r\n                        prevención. El terreno donde se encontraba la vivienda permanecerá acordonado hasta que finalicen las labores de\r\n                        peritaje técnico. Ingenieros estructurales evaluarán si es necesario retirar completamente los restos del\r\n                        inmueble para evitar riesgos adicionales. Las compañías aseguradoras ya han iniciado los trámites\r\n                        correspondientes para valorar la cobertura de los daños. El ayuntamiento estudia ofrecer ayudas extraordinarias\r\n                        para facilitar la reconstrucción o adquisición de una nueva vivienda. Autoridades regionales han reiterado su\r\n                        compromiso con la mejora de los estándares de seguridad en edificaciones residenciales antiguas. El caso sigue\r\n                        bajo investigación, y se espera que en los próximos días se confirme oficialmente la causa exacta del devastador\r\n                        incendio nocturno.', 'Un violento incendio declarado en la madrugada del lunes ha destruido por completo una vivienda unifamiliar\r\n                        situada en las afueras de Brno, en la República Checa. Las llamas comenzaron alrededor de las 2:17 de la\r\n                        madrugada, según confirmó el cuerpo regional de bomberos. Vecinos de la zona alertaron a los servicios de\r\n                        emergencia tras escuchar una fuerte explosión proveniente del interior de la vivienda. En cuestión de minutos,\r\n                        el fuego se extendió por la estructura de madera, dificultando las labores iniciales de contención. Al lugar\r\n                        acudieron cuatro dotaciones de bomberos que trabajaron durante más de tres horas para sofocar el incendio. Las\r\n                        condiciones meteorológicas, con temperaturas bajo cero y viento moderado, complicaron la intervención. La\r\n                        vivienda, construida hace más de 40 años, quedó completamente calcinada. El tejado colapsó poco después de la\r\n                        llegada de los equipos de emergencia. Afortunadamente, los ocupantes lograron evacuar el inmueble a tiempo tras\r\n                        percibir olor a humo en la planta baja. Ninguno de los miembros de la familia sufrió heridas graves. Las\r\n                        autoridades locales han acordonado la zona mientras se realizan las primeras evaluaciones estructurales y se\r\n                        asegura el perímetro. \r\n\r\nSegún fuentes oficiales, el incendio podría haberse originado en el sistema eléctrico del inmueble. Los\r\n                        investigadores no descartan un fallo en una instalación antigua que no había sido renovada recientemente.\r\n                        Especialistas en incendios han comenzado el análisis de los restos para determinar el punto exacto de ignición.\r\n                        El informe preliminar estará disponible en los próximos días. El alcalde de la localidad expresó su preocupación\r\n                        por el estado de muchas viviendas antiguas en la región, señalando la necesidad de revisar instalaciones\r\n                        eléctricas obsoletas. Vecinos cercanos afirmaron haber visto chispas en la parte trasera de la casa minutos\r\n                        antes de que las llamas fueran visibles desde la calle. Las autoridades han recordado la importancia de contar\r\n                        con detectores de humo funcionales, especialmente durante el invierno, cuando aumenta el uso de sistemas de\r\n                        calefacción. Por el momento, no se han encontrado indicios que apunten a un origen intencional del siniestro.\r\n\r\nLa familia afectada, compuesta por cuatro miembros, fue trasladada temporalmente a casa de unos familiares\r\n                        mientras se gestionan soluciones habitacionales de emergencia. Servicios sociales del municipio han activado un\r\n                        protocolo de asistencia para proporcionar apoyo económico inicial y cobertura básica. Organizaciones vecinales\r\n                        han iniciado una campaña solidaria para recaudar fondos y donar ropa, alimentos y productos esenciales. El\r\n                        impacto emocional ha sido significativo, especialmente para los dos menores que residían en la vivienda.\r\n                        Psicólogos municipales han ofrecido asistencia voluntaria para ayudar a la familia a afrontar la pérdida\r\n                        material y el trauma del suceso. Se estima que los daños materiales superan los 250.000 euros, aunque la cifra\r\n                        podría aumentar tras la evaluación completa.Expertos en seguridad advierten que los incendios domésticos aumentan durante los meses de invierno en Europa\r\n                        Central debido al mayor uso de calefacción eléctrica y sistemas de combustión. En la República Checa, los\r\n                        servicios de emergencia registraron el pasado año más de 15.000 intervenciones relacionadas con incendios\r\n                        estructurales. Las autoridades recomiendan realizar revisiones periódicas de instalaciones eléctricas y calderas\r\n                        para reducir riesgos. También se aconseja no sobrecargar enchufes múltiples y desconectar dispositivos\r\n                        eléctricos durante la noche cuando no estén en uso. El cuerpo de bomberos ha anunciado que intensificará\r\n                        campañas de concienciación ciudadana en las próximas semanas. Mientras continúan las investigaciones, la\r\n                        comunidad permanece conmocionada por un suceso que ha vuelto a poner sobre la mesa la importancia de la\r\n                        prevención. El terreno donde se encontraba la vivienda permanecerá acordonado hasta que finalicen las labores de\r\n                        peritaje técnico. Ingenieros estructurales evaluarán si es necesario retirar completamente los restos del\r\n                        inmueble para evitar riesgos adicionales. Las compañías aseguradoras ya han iniciado los trámites\r\n                        correspondientes para valorar la cobertura de los daños. El ayuntamiento estudia ofrecer ayudas extraordinarias\r\n                        para facilitar la reconstrucción o adquisición de una nueva vivienda. Autoridades regionales han reiterado su\r\n                        compromiso con la mejora de los estándares de seguridad en edificaciones residenciales antiguas. El caso sigue\r\n                        bajo investigación, y se espera que en los próximos días se confirme oficialmente la causa exacta del devastador\r\n                        incendio nocturno.', '2026-03-05', 1, 1, '2026-03-05 12:23:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticia_categoria`
--

CREATE TABLE `noticia_categoria` (
  `noticia_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `noticia_categoria`
--

INSERT INTO `noticia_categoria` (`noticia_id`, `categoria_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

CREATE TABLE `pais` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `continente` varchar(100) DEFAULT NULL,
  `bandera` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `pais`
--

INSERT INTO `pais` (`id`, `titulo`, `continente`, `bandera`) VALUES
(1, 'España', 'Europa', '🇪🇸');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preferencia`
--

CREATE TABLE `preferencia` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preferencia_categoria`
--

CREATE TABLE `preferencia_categoria` (
  `preferencia_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preferencia_medio`
--

CREATE TABLE `preferencia_medio` (
  `preferencia_id` int(11) NOT NULL,
  `medio_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preferencia_pais`
--

CREATE TABLE `preferencia_pais` (
  `preferencia_id` int(11) NOT NULL,
  `pais_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gmail` (`email`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `medio`
--
ALTER TABLE `medio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `noticia`
--
ALTER TABLE `noticia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `pais_id` (`pais_id`),
  ADD KEY `medio_id` (`medio_id`);

--
-- Indices de la tabla `noticia_categoria`
--
ALTER TABLE `noticia_categoria`
  ADD PRIMARY KEY (`noticia_id`,`categoria_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `preferencia`
--
ALTER TABLE `preferencia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `preferencia_categoria`
--
ALTER TABLE `preferencia_categoria`
  ADD PRIMARY KEY (`preferencia_id`,`categoria_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `preferencia_medio`
--
ALTER TABLE `preferencia_medio`
  ADD PRIMARY KEY (`preferencia_id`,`medio_id`),
  ADD KEY `medio_id` (`medio_id`);

--
-- Indices de la tabla `preferencia_pais`
--
ALTER TABLE `preferencia_pais`
  ADD PRIMARY KEY (`preferencia_id`,`pais_id`),
  ADD KEY `pais_id` (`pais_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `medio`
--
ALTER TABLE `medio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `noticia`
--
ALTER TABLE `noticia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pais`
--
ALTER TABLE `pais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `preferencia`
--
ALTER TABLE `preferencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `noticia`
--
ALTER TABLE `noticia`
  ADD CONSTRAINT `noticia_ibfk_1` FOREIGN KEY (`pais_id`) REFERENCES `pais` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `noticia_ibfk_2` FOREIGN KEY (`medio_id`) REFERENCES `medio` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `noticia_categoria`
--
ALTER TABLE `noticia_categoria`
  ADD CONSTRAINT `noticia_categoria_ibfk_1` FOREIGN KEY (`noticia_id`) REFERENCES `noticia` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `noticia_categoria_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preferencia`
--
ALTER TABLE `preferencia`
  ADD CONSTRAINT `preferencia_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preferencia_categoria`
--
ALTER TABLE `preferencia_categoria`
  ADD CONSTRAINT `preferencia_categoria_ibfk_1` FOREIGN KEY (`preferencia_id`) REFERENCES `preferencia` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `preferencia_categoria_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preferencia_medio`
--
ALTER TABLE `preferencia_medio`
  ADD CONSTRAINT `preferencia_medio_ibfk_1` FOREIGN KEY (`preferencia_id`) REFERENCES `preferencia` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `preferencia_medio_ibfk_2` FOREIGN KEY (`medio_id`) REFERENCES `medio` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preferencia_pais`
--
ALTER TABLE `preferencia_pais`
  ADD CONSTRAINT `preferencia_pais_ibfk_1` FOREIGN KEY (`preferencia_id`) REFERENCES `preferencia` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `preferencia_pais_ibfk_2` FOREIGN KEY (`pais_id`) REFERENCES `pais` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
