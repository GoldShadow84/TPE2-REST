-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2022 a las 01:15:59
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_series`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platform`
--

CREATE TABLE `platform` (
  `id_platform` int(11) NOT NULL,
  `company` varchar(45) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `platform`
--

INSERT INTO `platform` (`id_platform`, `company`, `price`) VALUES
(1, 'netflix', 800),
(2, 'hbo', 500),
(3, 'Prime', 400),
(4, 'disney plus', 800);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reviews`
--

CREATE TABLE `reviews` (
  `id_review` int(11) NOT NULL,
  `author` varchar(45) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `id_Serie_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `reviews`
--

INSERT INTO `reviews` (`id_review`, `author`, `comment`, `id_Serie_fk`) VALUES
(1, 'Thunder', 'LA MEJOR SERIE DE LA HISTORIA.\r\nLa profundidad de los personajes, La sensación de cómo todo se agranda y, al mismo tiempo, desciende a un pozo sin fondo, Las geniales actuaciones por parte del elenco de actores y actrices. Una historia original que, llegado a un punto, no puedes parar de consumir capítulo tras capítulo.\r\nGran dirección y producción. Momentos que se te quedan guardados en la mente Adrenalina.', 14),
(2, 'Piero Perez', 'UNA SOBREDOSIS DE EXCELENCIA Como ya se había mencionado esta serie es más que una historia, es el camino impecable de un antagonista.\r\n\r\nEs verdaderamente una genialidad, más allá del increíble guion, es una cruda representación de como un simple mal día te puede transformar en otra persona completamente distinta. La serie tiene una evolución no solo en cuanto a calidad de narración, si no en personajes.\r\nSe explora muchos personajes a lo largo de este proyecto, pero sin duda alguna los princip', 14),
(3, 'David Caballero', 'Recién en el año 2018, cuando se estaba poniendo de moda nuevamente Breaking Bad entre el público joven y adolescente, fue cuando descubrí este curioso e interesante universo de la pantalla chica. Amé por completo Breaking Bad, de inicio a fin, considerándose una de mis series favoritas por todos sus aspectos desde el guión tan elaborado, hasta la fotografía detallada de los ambientes y los montajes tan atrayentes. Tanta fue mi fascinación que, al percatarme de la existencia de Better Call Saul,', 11),
(4, 'Diego Martinez', 'Una obra de arte, es todo lo menos que puedo decir por esta genial serie, una estupenda mirada hacia el mundo del crimen, a diferencia de otras esta nunca pierde su encanto y haces que estés muy pendiente en que pasara en el siguiente capitulo, los personajes te llegan a importar mucho, al grado que cuando algo malo le pasa a uno sientes empatía por el, también cabe destacar lo interesante que es ver como evolucionan y cambian los personajes para bien o para mal, que literal son completamente di', 14),
(5, 'JohanTrain', 'Uff, pero que serie mas Chingona, Breaking Bad tiene bien merecido el titulo de mejor serie de la historia, protagonizada por un actor que realmente dio todo de si mismo a pesar de su currículum en Malcolm el de enmedio. La serie tiene un Concepto realmente interesante y nunca antes visto, sus protagonistas tienes cualidades suficientes para volverse tu favorito', 14),
(6, 'NoryReinoso', 'Temporadas 1 a 6, La serie es fabulosa!!!...No tiene desperdicios. Cada actor está perfecto en su personaje. Cada detalle, cada palabra, cada gesto: perfectos!!! Bob Odenkyrk, es increíble, un actor tan espectacular como único en su género.(recuerdo las escenas del desierto, y cuando el malvado Lalo, le hace repetir una y otra vez cómo hizo para salir con vida, sus expresiones...ayyyyy).....Geniales Reha, Jonathan Banks, Tony Dalton, Giancarlo Espósito, Michael Mando (Nacho)', 11),
(7, 'Maribel Garcia', 'No he visto nunca Breaking Bad(para quién no la haya visto nunca no pasa nada porque es una precuela por lo tanto todo lo de breaking bad es después, se puede ver sin que te preguntes \"y esto qué tiene que ver?\") , pero sí en 2 semanas está serie, y es una auténtica pasada!!!! Es tan sumamente buena la interpretación del actor de Jimmy que hasta me llega a sacar demasiado desquicio muy muy real y en ocasiones me desespera (mi sensación claro) jajajaja?. Me encanta! Es brutal, demasiado, muy heav', 11),
(8, 'Mateo Airaudo', 'Maravillosa serie. Me vi las 4 temporadas el año pasado en 1 mes y tuve que esperar con locura la 5. Esta quinta me está volando la cabeza y no puedo esperar para el final. Magistralmente actuada, la fotografía es una joya en cada toma, el guion es sublime y me encanta el ritmo lento de la historia. A diferencia de Breaking Bad', 11),
(9, 'ZenoGod', 'Estoy viendo la v temporada de Better call Saul, solo se me ocurre decir (en dos palabras) que es IMPRESI ONANTE!!!, hay series francamente buenas... Los Soprano, Breakind Bad y otras...Pero no alcanzan la categoría de las andanzas de este mentiroso, taimado y pintoresco Abogado. Si a esta muy buena historia le añadimos: Unas interpretaciones magníficas, una fotografía fenomenal, una música que le viene al pelo y una Dirección estupenda...De la coctelera surge la Madre de todas las series...Una ', 11),
(10, 'QueartEr3', 'Cuando se anunció “House of the Dragon”, incluso los fanáticos de “Game of Thrones” estaban preocupados. ¿ David Benioff y DB Weiss estarían al frente de la serie? ¿Estaría involucrado George RR Martin? Estas preguntas son importantes, no solo para los fanáticos acérrimos de \"Game of Thrones\", sino también para los espectadores ocasionales. Después de un gran éxito comercial y de crítica desde su estreno en 2011, la serie ganadora de un Emmy terminó en 2019 con muchas críticas, desde las tramas ', 16),
(11, 'GuiselaMartos', 'Debo admitir que esperaba esta serie con mucha expectativa. Siempre pensé que iban a tener que hacer mucho esfuerzo por game of thrones. Pero para mi alegría no solo nos están trayendo una muy buena historia (estará basada en la danza de los dragones por lo cual tendremos una historia y batallas épicas, los directores y guionistas están haciendo un excelente trabajo en cuanto a la historia) sino que también, simplemente es algo genial de ver. Antes del primer capítulo (voy por el tercero) debo a', 16),
(12, 'FesusBenabente', 'Mi crítica será basada en el primer capítulo y muy probablemente modifique la reseña en el futuro cuando salgan los restantes.Este primer capítulo mantiene el estilo, la música y la escencia de Game of Thrones. Las actuaciones están a muy bien nivel y la belleza de Milly Alcock es simplemente cautivadora.Algo que extrañé es ver un intro epico como siempre lo fue en Game of Thrones, ese tipo de intro que no da ganas de saltar por lo grandioso que es, sin embargo al parecer House of the Dragon no ', 16),
(13, 'sanchez84', 'de las mejores series de marvel', 20),
(14, 'andrezzez', 'muy buena serie', 20),
(15, 'yuyuyusz', 'la historia es muy buena, la mejor hasta ahora de marvel studios', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `serie`
--

CREATE TABLE `serie` (
  `id_serie` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `genre` varchar(45) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `id_platform_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `serie`
--

INSERT INTO `serie` (`id_serie`, `name`, `genre`, `image`, `id_platform_fk`) VALUES
(11, 'Better Call Saul', 'drama', 'img/task633f2029073d1.jpg', 1),
(14, 'Breaking Bad', 'drama', 'img/series633f20fbc2679.jpg', 1),
(16, 'House of Dragon', 'accion-drama', 'img/series63486a463e3a2.jpg', 2),
(20, 'Loki', 'fantasia', NULL, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_users`, `email`, `password`) VALUES
(1, 'javierse9@gmail.com', '$2y$10$DKyMugeMtJ9gdaKw3U6U8uWoL1WHuDp/6/TQxOqgeKz0ZYgnVb6c.'),
(2, 'javierondicol84@gmail.com', '$2y$10$M2STCmVS0sGpkirKuJLrb.be8CMUOJ31QJvmLSNUSWEa973e0vMlm');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `platform`
--
ALTER TABLE `platform`
  ADD PRIMARY KEY (`id_platform`);

--
-- Indices de la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `FK_id_serie` (`id_Serie_fk`) USING BTREE;

--
-- Indices de la tabla `serie`
--
ALTER TABLE `serie`
  ADD PRIMARY KEY (`id_serie`),
  ADD KEY `id_platform_fk` (`id_platform_fk`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `platform`
--
ALTER TABLE `platform`
  MODIFY `id_platform` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `serie`
--
ALTER TABLE `serie`
  MODIFY `id_serie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`id_Serie_fk`) REFERENCES `serie` (`id_serie`);

--
-- Filtros para la tabla `serie`
--
ALTER TABLE `serie`
  ADD CONSTRAINT `id_platform_fk` FOREIGN KEY (`id_platform_fk`) REFERENCES `platform` (`id_platform`),
  ADD CONSTRAINT `serie_ibfk_1` FOREIGN KEY (`id_platform_fk`) REFERENCES `platform` (`id_platform`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
