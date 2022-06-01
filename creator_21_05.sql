-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 21 2022 г., 20:19
-- Версия сервера: 8.0.24
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `creator`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int NOT NULL,
  `Title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`CategoryID`, `Title`) VALUES
(1, 'IT-технологии'),
(2, 'Бизнес-идеи'),
(3, 'Программное обеспечение'),
(4, 'Web'),
(5, 'Приборостроение');

-- --------------------------------------------------------

--
-- Структура таблицы `fees`
--

CREATE TABLE `fees` (
  `FeeID` int NOT NULL,
  `Title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `fees`
--

INSERT INTO `fees` (`FeeID`, `Title`) VALUES
(1, 'единовременная выплата'),
(2, 'постоянный процент с прибыли');

-- --------------------------------------------------------

--
-- Структура таблицы `ideas`
--

CREATE TABLE `ideas` (
  `IdeaID` int NOT NULL,
  `Title` text NOT NULL,
  `CategoryID` int NOT NULL,
  `Description` text NOT NULL,
  `UserID` int NOT NULL,
  `FeeID` int NOT NULL,
  `FeeAmount` int NOT NULL,
  `StageID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `ideas`
--

INSERT INTO `ideas` (`IdeaID`, `Title`, `CategoryID`, `Description`, `UserID`, `FeeID`, `FeeAmount`, `StageID`) VALUES
(1, 'Blockchain Пирожковая', 1, 'У меня есть великолепная идея по открытию виртуального кафе с пирожками. Проект будет работать с использованием технологии блокчейн. Готов раскрыть детали энтузиасту или команде разработчиков за определенный процент с продаж ;)', 1, 2, 5, 1),
(7, '\"Веселая ферма\"', 1, '\"Веселая ферма\" - это ферма, основанная на технологии blockchain.', 2, 1, 15000, 2),
(8, 'Экологичная переработка отходов', 5, 'Какие технологии можно внедрить в производство, чтобы переработка отходов была более экологичной?', 2, 2, 5, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `stages`
--

CREATE TABLE `stages` (
  `StageID` int NOT NULL,
  `Title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `stages`
--

INSERT INTO `stages` (`StageID`, `Title`) VALUES
(1, 'идея'),
(2, 'концепт'),
(3, 'исследование'),
(4, 'прототип'),
(5, 'испытание прототипа'),
(6, 'промышленный образец'),
(7, 'подготовка к производству'),
(8, 'мелкосерийное производство'),
(9, 'серийное производство');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `UserID` int NOT NULL,
  `FIO` text NOT NULL,
  `Login` varchar(20) NOT NULL,
  `Password` varchar(40) NOT NULL,
  `E-mail` varchar(40) NOT NULL,
  `Career` text NOT NULL,
  `Status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`UserID`, `FIO`, `Login`, `Password`, `E-mail`, `Career`, `Status`) VALUES
(1, 'Хазов Кирилл Андреевич', 'marioorg', '7288edd0fc3ffcbe93a0cf06e3568e28521687bc', 'kirill.xazov@mail.ru', 'Программирование', 1),
(2, 'Тест Тест Тест', 'test', '7288edd0fc3ffcbe93a0cf06e3568e28521687bc', 'test@mail.ru', 'Web-дизайн', 0),
(3, 'Иванов Иван Иванович', 'ivanov', '00c612dd43867555ad897cb738246cc64f2967cb', 'ivanov@mail.ru', 'Тестировщик', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Индексы таблицы `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`FeeID`);

--
-- Индексы таблицы `ideas`
--
ALTER TABLE `ideas`
  ADD PRIMARY KEY (`IdeaID`);

--
-- Индексы таблицы `stages`
--
ALTER TABLE `stages`
  ADD PRIMARY KEY (`StageID`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `fees`
--
ALTER TABLE `fees`
  MODIFY `FeeID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `ideas`
--
ALTER TABLE `ideas`
  MODIFY `IdeaID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `stages`
--
ALTER TABLE `stages`
  MODIFY `StageID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
