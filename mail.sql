-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Фев 19 2024 г., 10:45
-- Версия сервера: 8.0.31
-- Версия PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mail`
--

-- --------------------------------------------------------

--
-- Структура таблицы `list_of_services`
--

CREATE TABLE `list_of_services` (
  `Message_ID` int NOT NULL,
  `Services_idServices` int NOT NULL,
  `date_services` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `list_of_services`
--

INSERT INTO `list_of_services` (`Message_ID`, `Services_idServices`, `date_services`) VALUES
(19, 1, '2024-02-03'),
(21, 2, '2024-02-03'),
(22, 2, '2024-02-03'),
(23, 1, '2024-02-03'),
(25, 2, '2024-02-04'),
(26, 1, '2024-02-04'),
(27, 2, '2024-02-04'),
(29, 1, '2024-02-04'),
(31, 1, '2024-02-10'),
(34, 1, '2024-02-13'),
(36, 2, '2024-02-19'),
(37, 2, '2024-02-19');

-- --------------------------------------------------------

--
-- Структура таблицы `message`
--

CREATE TABLE `message` (
  `idMessage` int NOT NULL,
  `sender` int NOT NULL,
  `recipient` int DEFAULT NULL,
  `senders_post_office` int NOT NULL,
  `recipients_post_office` int NOT NULL,
  `type_of_message` int NOT NULL,
  `status` int NOT NULL,
  `date_of_dispatch` date NOT NULL,
  `date_of_receipt` date NOT NULL,
  `Comment` varchar(45) COLLATE utf8mb3_bin DEFAULT NULL,
  `locked` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `message`
--

INSERT INTO `message` (`idMessage`, `sender`, `recipient`, `senders_post_office`, `recipients_post_office`, `type_of_message`, `status`, `date_of_dispatch`, `date_of_receipt`, `Comment`, `locked`) VALUES
(19, 1, 4, 1, 2, 2, 5, '2024-01-29', '2024-02-11', '', 0),
(20, 3, 4, 1, 3, 2, 5, '2024-02-03', '2024-02-12', '', 3),
(21, 3, 4, 2, 1, 2, 4, '2024-01-26', '2024-02-07', '', 3),
(22, 2, 5, 2, 3, 2, 3, '2024-01-29', '2024-02-24', '', 0),
(23, 4, 2, 2, 1, 2, 4, '2024-02-02', '2024-02-09', '', 6),
(24, 4, 5, 2, 1, 3, 4, '2024-02-03', '2024-02-15', '', 6),
(25, 2, 1, 2, 1, 3, 4, '2024-02-04', '2024-02-27', '', 0),
(26, 5, 3, 3, 2, 2, 4, '2024-01-31', '2024-02-01', '', 0),
(27, 2, 3, 3, 1, 2, 4, '2024-02-01', '2024-03-01', '', 0),
(28, 3, 1, 3, 2, 2, 4, '2024-02-02', '2024-02-14', '', 3),
(29, 3, 5, 3, 2, 3, 4, '2024-02-03', '2024-02-16', '', 3),
(30, 4, 5, 2, 3, 2, 3, '2024-02-10', '2024-02-23', '', 6),
(31, 1, 2, 2, 3, 2, 3, '2024-02-01', '2024-02-14', '', 0),
(33, 1, 3, 1, 3, 3, 5, '2024-02-12', '2024-02-22', 'Все работает                                 ', 0),
(34, 1, 2, 1, 3, 2, 5, '2024-02-13', '2024-02-23', 'Отлично работает!                            ', 0),
(36, 1, 3, 1, 2, 2, 3, '2024-02-19', '2024-02-19', '                                    ', 0),
(37, 2, 4, 1, 3, 2, 3, '2024-02-19', '2024-03-01', '', 0);

--
-- Триггеры `message`
--
DELIMITER $$
CREATE TRIGGER `after_update_status` AFTER UPDATE ON `message` FOR EACH ROW BEGIN
    IF NEW.status = 4 THEN
        DELETE FROM Mail.Way WHERE message_ID = NEW.idMessage;
        DELETE FROM payment WHERE message = NEW.idMessage;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `payment`
--

CREATE TABLE `payment` (
  `idPayment` int NOT NULL,
  `message` int NOT NULL,
  `sum` float NOT NULL,
  `date_payment` date NOT NULL,
  `payment_methods` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `payment`
--

INSERT INTO `payment` (`idPayment`, `message`, `sum`, `date_payment`, `payment_methods`) VALUES
(63, 20, 150, '2024-02-04', 2),
(65, 22, 200, '2024-02-04', 2),
(68, 25, 200, '2024-02-04', 2),
(69, 26, 275, '2024-02-04', 1),
(70, 27, 200, '2024-02-04', 2),
(73, 30, 150, '2024-02-10', 4),
(74, 31, 275, '2024-02-10', 4),
(76, 33, 1, '2024-02-12', 2),
(77, 34, 125, '2024-02-13', 2),
(78, 19, 275, '2024-02-16', 1),
(80, 36, 50, '2024-02-19', 2),
(81, 37, 200, '2024-02-19', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `post_office`
--

CREATE TABLE `post_office` (
  `idPost_office` int NOT NULL,
  `name` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `address` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `city` varchar(45) COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `post_office`
--

INSERT INTO `post_office` (`idPost_office`, `name`, `address`, `city`) VALUES
(1, 'Почтовое отделение №1', 'ул. Савеловская, 1', 'Санкт-Петербург'),
(2, 'Почтовое отделение №2', 'пр. Победы, 20', 'Санкт-Петербург'),
(3, 'Почтовое отделение №3', 'Ул. Ленина д.25', 'Москва');

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `idReviews` int NOT NULL,
  `user` int NOT NULL,
  `text_review` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `date_of_creation` date NOT NULL,
  `status` int NOT NULL,
  `worker` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`idReviews`, `user`, `text_review`, `date_of_creation`, `status`, `worker`) VALUES
(1, 1, 'Все классно, все работает!', '2024-02-13', 2, 20),
(2, 1, 'Все классно, все работает!', '2024-02-13', 2, 20),
(3, 1, 'Все классно, все работает!', '2024-02-13', 2, 20),
(4, 1, 'выывафывафыва', '2024-02-17', 2, 13),
(5, 1, 'ывафывафыв', '2024-02-17', 3, 13),
(6, 1, 'ывафывачсмся', '2024-02-17', 3, 20),
(7, 1, '234234', '2024-02-17', 2, 20),
(8, 3, 'ryuiytjhh', '2024-02-17', 2, 13),
(9, 3, 'tyu67utyhur', '2024-02-17', 3, 13),
(10, 3, '65rtthfr65uj67i', '2024-02-17', 3, 13),
(11, 3, '43rfsew34r', '2024-02-17', 2, 13),
(12, 3, 'sdvbs', '2024-02-17', 3, 13),
(13, 1, 'Классс Класс Класс', '2024-02-18', 2, 20);

-- --------------------------------------------------------

--
-- Структура таблицы `services`
--

CREATE TABLE `services` (
  `idServices` int NOT NULL,
  `name` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `description` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `cost` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `services`
--

INSERT INTO `services` (`idServices`, `name`, `description`, `cost`) VALUES
(1, 'EXPRESS', 'Доставим в течении 2-3 дней', 125),
(2, 'TO Time', 'Доставим в определенный час', 50);

-- --------------------------------------------------------

--
-- Структура таблицы `types of payment methods`
--

CREATE TABLE `types of payment methods` (
  `idTypes_of_payment_methods` int NOT NULL,
  `payment_methods` varchar(45) COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `types of payment methods`
--

INSERT INTO `types of payment methods` (`idTypes_of_payment_methods`, `payment_methods`) VALUES
(1, 'Наличными'),
(2, 'Картой'),
(3, 'Онлайн'),
(4, 'QR - код');

-- --------------------------------------------------------

--
-- Структура таблицы `types_of_message_statuses`
--

CREATE TABLE `types_of_message_statuses` (
  `idTypes_of_statuses` int NOT NULL,
  `Type_status` varchar(45) COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `types_of_message_statuses`
--

INSERT INTO `types_of_message_statuses` (`idTypes_of_statuses`, `Type_status`) VALUES
(1, 'Зарегистрировано'),
(2, 'В обработке'),
(3, 'В пути'),
(4, 'Доставлено'),
(5, 'Отменено');

-- --------------------------------------------------------

--
-- Структура таблицы `types_of_review_statuses`
--

CREATE TABLE `types_of_review_statuses` (
  `idTypes_of_statuses` int NOT NULL,
  `name` varchar(45) COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `types_of_review_statuses`
--

INSERT INTO `types_of_review_statuses` (`idTypes_of_statuses`, `name`) VALUES
(1, 'В обработке'),
(2, 'Принято'),
(3, 'Отклонено');

-- --------------------------------------------------------

--
-- Структура таблицы `type_of_message`
--

CREATE TABLE `type_of_message` (
  `idType_of_message` int NOT NULL,
  `name` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `weight_g` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `width_m` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `height_m` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `length_m` varchar(45) COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `type_of_message`
--

INSERT INTO `type_of_message` (`idType_of_message`, `name`, `weight_g`, `width_m`, `height_m`, `length_m`) VALUES
(1, 'Письмо', '20', '0.3', '0.005', '0.3'),
(2, 'Бандероль', '200', '0.5', '0.1', '0.5'),
(3, 'Посылка', '1000', '1', '1', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `type_post`
--

CREATE TABLE `type_post` (
  `idtype_post` int NOT NULL,
  `name` varchar(45) COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `type_post`
--

INSERT INTO `type_post` (`idtype_post`, `name`) VALUES
(1, 'Стажёр'),
(2, 'Клерк'),
(3, 'БОСС');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `idUser` int NOT NULL,
  `name` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `surname` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `email` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `phone_number` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `password` varchar(45) COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`idUser`, `name`, `surname`, `email`, `phone_number`, `password`) VALUES
(1, 'Ленка', 'Сучка', 'Lenka@gmail.com', '+7 (425) 456-64-36', 'Lenka228355'),
(2, 'Иван', 'Васильев', 'Ivan@gmail.com', '+7 (425) 456-64-36', 'Bdfy12312343'),
(3, 'Егор', 'Канлесников', 'Egorkalox@gmail.com', '+7 (234) 314-28-41', 'Tuf123456234'),
(4, 'Пётр', 'Первый', 'PETYA@gmail.com', '+7 (342) 342-34-12', 'waersdfa231'),
(5, 'Александр', 'Белов', 'Sashabelyj@gmail.com', '+7 (686) 874-65-33', 'gsdhfhgs453'),
(6, 'Леша', 'Калоша', 'Soska@gmail.com', '+7 (345) 325-44-62', 'jeshjkg342');

-- --------------------------------------------------------

--
-- Структура таблицы `way`
--

CREATE TABLE `way` (
  `id` int NOT NULL,
  `message_ID` int NOT NULL,
  `post_offis_id` int NOT NULL,
  `date` date NOT NULL,
  `accepted_worker` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `way`
--

INSERT INTO `way` (`id`, `message_ID`, `post_offis_id`, `date`, `accepted_worker`) VALUES
(2, 20, 1, '2024-02-04', 2),
(3, 22, 1, '2024-02-10', 2),
(4, 22, 2, '2024-02-04', 6),
(5, 30, 1, '2024-02-10', 2),
(6, 30, 2, '2024-02-10', NULL),
(7, 31, 2, '2024-02-10', 6),
(9, 22, 2, '2024-02-10', 6),
(10, 20, 3, '2024-02-10', 9),
(11, 31, 1, '2024-02-13', 2),
(12, 19, 1, '2024-02-16', 2),
(13, 37, 1, '2024-02-19', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `worker`
--

CREATE TABLE `worker` (
  `idWorker` int NOT NULL,
  `name` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `suname` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `post` int NOT NULL,
  `post_office_work` int NOT NULL,
  `password` varchar(45) COLLATE utf8mb3_bin NOT NULL,
  `login` varchar(45) COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Дамп данных таблицы `worker`
--

INSERT INTO `worker` (`idWorker`, `name`, `suname`, `post`, `post_office_work`, `password`, `login`) VALUES
(2, 'Кирил', 'Сосенов', 2, 1, 'Cjcyjd123456', 'kiril_2'),
(6, 'Константин', 'Канлесников', 2, 2, '12345678kons', 'konstantin_1'),
(9, 'Екатерина', 'Смирнова', 2, 3, 'wresafd3242', 'ekaterina_1'),
(10, 'Петя', 'Сёмочкин', 3, 1, 'dsfsdf324234', 'petya_1'),
(11, 'Сеня', 'Петров', 3, 2, 'sdfsgf45345', 'senya_1'),
(13, 'Гандон', 'Дон', 1, 3, 'sddfgsajf22', 'gandon_1'),
(19, 'Александр', 'Семочкин', 2, 1, 'sadfas123qwe', 'aleksandr_1'),
(20, 'Сеня', 'Чижов', 1, 2, 'ersftgrefg345345', 'senya_2');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `list_of_services`
--
ALTER TABLE `list_of_services`
  ADD PRIMARY KEY (`Message_ID`,`Services_idServices`),
  ADD KEY `fk_Message_has_Services_Message1_idx` (`Services_idServices`) INVISIBLE,
  ADD KEY `fk_Message_has_Services_Services1_idx` (`Message_ID`) INVISIBLE;

--
-- Индексы таблицы `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`idMessage`),
  ADD KEY `send_idx` (`sender`) INVISIBLE,
  ADD KEY `receive_idx` (`recipient`),
  ADD KEY `Post. otp._idx` (`senders_post_office`),
  ADD KEY `Post.get.._idx` (`recipients_post_office`),
  ADD KEY `stat._idx` (`status`),
  ADD KEY `determine_type_of_parcel_idx` (`type_of_message`);

--
-- Индексы таблицы `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`idPayment`),
  ADD KEY `mes._idx` (`message`),
  ADD KEY `type_of_pay._idx` (`payment_methods`);

--
-- Индексы таблицы `post_office`
--
ALTER TABLE `post_office`
  ADD PRIMARY KEY (`idPost_office`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`idReviews`),
  ADD KEY `applicant_idx` (`user`),
  ADD KEY `stat. type_idx` (`status`),
  ADD KEY `sup.worker_idx` (`worker`);

--
-- Индексы таблицы `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`idServices`);

--
-- Индексы таблицы `types of payment methods`
--
ALTER TABLE `types of payment methods`
  ADD PRIMARY KEY (`idTypes_of_payment_methods`);

--
-- Индексы таблицы `types_of_message_statuses`
--
ALTER TABLE `types_of_message_statuses`
  ADD PRIMARY KEY (`idTypes_of_statuses`);

--
-- Индексы таблицы `types_of_review_statuses`
--
ALTER TABLE `types_of_review_statuses`
  ADD PRIMARY KEY (`idTypes_of_statuses`);

--
-- Индексы таблицы `type_of_message`
--
ALTER TABLE `type_of_message`
  ADD PRIMARY KEY (`idType_of_message`);

--
-- Индексы таблицы `type_post`
--
ALTER TABLE `type_post`
  ADD PRIMARY KEY (`idtype_post`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- Индексы таблицы `way`
--
ALTER TABLE `way`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_message_has_post_offis2_idx1` (`message_ID`),
  ADD KEY `post_offis_id` (`post_offis_id`) USING BTREE,
  ADD KEY `accep.work._idx` (`accepted_worker`) USING BTREE;

--
-- Индексы таблицы `worker`
--
ALTER TABLE `worker`
  ADD PRIMARY KEY (`idWorker`),
  ADD KEY `post.off.work_idx` (`post_office_work`),
  ADD KEY `type_of_post_idx` (`post`) INVISIBLE;

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `message`
--
ALTER TABLE `message`
  MODIFY `idMessage` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT для таблицы `payment`
--
ALTER TABLE `payment`
  MODIFY `idPayment` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT для таблицы `post_office`
--
ALTER TABLE `post_office`
  MODIFY `idPost_office` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `idReviews` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `types of payment methods`
--
ALTER TABLE `types of payment methods`
  MODIFY `idTypes_of_payment_methods` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `types_of_message_statuses`
--
ALTER TABLE `types_of_message_statuses`
  MODIFY `idTypes_of_statuses` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `types_of_review_statuses`
--
ALTER TABLE `types_of_review_statuses`
  MODIFY `idTypes_of_statuses` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `type_of_message`
--
ALTER TABLE `type_of_message`
  MODIFY `idType_of_message` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `type_post`
--
ALTER TABLE `type_post`
  MODIFY `idtype_post` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `way`
--
ALTER TABLE `way`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `worker`
--
ALTER TABLE `worker`
  MODIFY `idWorker` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `list_of_services`
--
ALTER TABLE `list_of_services`
  ADD CONSTRAINT `fk_Message_has_Services_Message1` FOREIGN KEY (`Message_ID`) REFERENCES `message` (`idMessage`),
  ADD CONSTRAINT `fk_Message_has_Services_Services1` FOREIGN KEY (`Services_idServices`) REFERENCES `services` (`idServices`);

--
-- Ограничения внешнего ключа таблицы `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `determine_type_of_parcel` FOREIGN KEY (`type_of_message`) REFERENCES `type_of_message` (`idType_of_message`),
  ADD CONSTRAINT `Post. otp.` FOREIGN KEY (`senders_post_office`) REFERENCES `post_office` (`idPost_office`),
  ADD CONSTRAINT `Post.get.` FOREIGN KEY (`recipients_post_office`) REFERENCES `post_office` (`idPost_office`),
  ADD CONSTRAINT `receive` FOREIGN KEY (`recipient`) REFERENCES `user` (`idUser`),
  ADD CONSTRAINT `send` FOREIGN KEY (`sender`) REFERENCES `user` (`idUser`),
  ADD CONSTRAINT `stat.` FOREIGN KEY (`status`) REFERENCES `types_of_message_statuses` (`idTypes_of_statuses`);

--
-- Ограничения внешнего ключа таблицы `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `mes.` FOREIGN KEY (`message`) REFERENCES `message` (`idMessage`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `type_of_pay.` FOREIGN KEY (`payment_methods`) REFERENCES `types of payment methods` (`idTypes_of_payment_methods`);

--
-- Ограничения внешнего ключа таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `applicant` FOREIGN KEY (`user`) REFERENCES `user` (`idUser`),
  ADD CONSTRAINT `stat. type` FOREIGN KEY (`status`) REFERENCES `types_of_review_statuses` (`idTypes_of_statuses`),
  ADD CONSTRAINT `sup.worker` FOREIGN KEY (`worker`) REFERENCES `worker` (`idWorker`);

--
-- Ограничения внешнего ключа таблицы `way`
--
ALTER TABLE `way`
  ADD CONSTRAINT `accep.work` FOREIGN KEY (`accepted_worker`) REFERENCES `worker` (`idWorker`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_message_has_post_offis1` FOREIGN KEY (`message_ID`) REFERENCES `message` (`idMessage`),
  ADD CONSTRAINT `fk_message_has_post_offis2` FOREIGN KEY (`post_offis_id`) REFERENCES `post_office` (`idPost_office`);

--
-- Ограничения внешнего ключа таблицы `worker`
--
ALTER TABLE `worker`
  ADD CONSTRAINT `post.off.work` FOREIGN KEY (`post_office_work`) REFERENCES `post_office` (`idPost_office`),
  ADD CONSTRAINT `type_of_post` FOREIGN KEY (`post`) REFERENCES `type_post` (`idtype_post`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
