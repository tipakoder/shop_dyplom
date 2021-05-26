-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 26 2021 г., 06:27
-- Версия сервера: 8.0.19
-- Версия PHP: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `shop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `account`
--

CREATE TABLE `account` (
  `id` int NOT NULL,
  `type` enum('admin','user') NOT NULL DEFAULT 'user',
  `name` varchar(60) NOT NULL,
  `email` varchar(120) NOT NULL,
  `telephone` varchar(12) NOT NULL,
  `login` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `account`
--

INSERT INTO `account` (`id`, `type`, `name`, `email`, `telephone`, `login`, `password`) VALUES
(1, 'admin', 'Администратор', 'admin@shop.d', '89198460573', 'admin', '$2y$10$o6pov5fFw3uPi6QRXU/fKONfjiRWwYmPmd5gOtk/WVzleN.i/9cvi'),
(2, 'user', 'Данил', 'pepega@gmail.com', '89198460573', 'user', '$2y$10$yb6SKskexS1drKyeBh8apuG3Vhdpx8jJwqKTP/Xill1Q3oZrMnomm');

-- --------------------------------------------------------

--
-- Структура таблицы `account_session`
--

CREATE TABLE `account_session` (
  `id` int NOT NULL,
  `account_id` int NOT NULL,
  `sessionkey` varchar(255) NOT NULL,
  `timestamp` int NOT NULL,
  `ip` varchar(45) NOT NULL,
  `active` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `account_session`
--

INSERT INTO `account_session` (`id`, `account_id`, `sessionkey`, `timestamp`, `ip`, `active`) VALUES
(1, 1, 'c4bf98fb423fd34bdc880e0859bec1905dbb2bf1a8416537cc59a184abb88af6', 1621138167, '', 1),
(2, 1, '7b4a7af54b700715836ad77340bbfbfe63927ab2c3c904e858c536125c5b5115', 1621141757, '127.0.0.1', 1),
(3, 2, 'cf370dca39e43c53607d189b30574b2b63f70011853ffe0215f2a54ea6a91702', 1621149321, '127.0.0.1', 0),
(4, 1, '803699439a0a2e6290e33ab85d6e5fee0a97caeafa9657a2a29a04b9a1773198', 1621191411, '127.0.0.1', 0),
(5, 1, 'a567eeef02cf3e2b3ad1b37a64e4f829c038f66d7f4b30853505176853124cc4', 1621451858, '127.0.0.1', 0),
(6, 2, '72f852d46015caa9928d89bb3880812e38d481c8e15f7eea1e926d0edf54206a', 1621452046, '127.0.0.1', 0),
(7, 2, 'fd8322edb554f377b76e8542093158798c4425570c885fa280fc3fe6203fe55b', 1621634079, '127.0.0.1', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(12, 'Головные уборы'),
(13, 'Аксессуары'),
(14, 'Брюки'),
(15, 'Рубашки'),
(16, 'Термобелье'),
(17, 'Спорт'),
(18, 'Футболки');

-- --------------------------------------------------------

--
-- Структура таблицы `delivery_service`
--

CREATE TABLE `delivery_service` (
  `id` int NOT NULL,
  `name` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `min_price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `delivery_service`
--

INSERT INTO `delivery_service` (`id`, `name`, `title`, `min_price`) VALUES
(2, 'cdek', 'СДЕК', 350),
(3, 'delivery', 'Delivery Club', 150);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `delivery_service_id` int NOT NULL,
  `address` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `promocode_id` int DEFAULT '0',
  `notes` text COLLATE utf8mb4_general_ci,
  `paid` enum('y','n') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders_check`
--

CREATE TABLE `orders_check` (
  `id` int NOT NULL,
  `orders_id` int NOT NULL,
  `card_number` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `card_date` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `card_cvc` int NOT NULL,
  `card_owner` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `total_price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders_product`
--

CREATE TABLE `orders_product` (
  `orders_id` int NOT NULL,
  `product_id` int NOT NULL,
  `count` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `name` varchar(120) NOT NULL,
  `photo` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `price` int NOT NULL,
  `on_sale` enum('y','n') NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `name`, `photo`, `description`, `price`, `on_sale`) VALUES
(12, 'БЕЙСБОЛКА 5.11', '/content/1621203210_product.jpg', 'Имиджевые (пафосные) бейсболки. По сути представляют собой классические кепки с козырьком, изготовленные из легкого дышащего материала. На внешней части вышиты соответствующие надписи – рисунки. Предназначены для повседневного ношения в условиях жаркого климата или в летний период, т.к. отлично прикрывают глаза от прямых солнечных лучей. Тактические варианты бейсболок часто используют РМС (частные военные структуры), а также полицейские подразделения как часть униформы.\r\n\r\nПлюсы:\r\n• Удобная конструкция ;\r\n• Большой козырек;\r\n• Для жаркого климата;\r\n\r\nЦвет: Бежевый, олива.\r\n', 490, 'y'),
(13, 'ОЧКИ OAKLEY', '/content/1621204348_product.jpg', 'Очки Oakley без Полароида\r\n\r\nИмеют в комплекте заменяемые Линзы разных Цветов и покрытий. Желтые стекла, Темные стекла, Голубые Стекла, Прозрачные стекла, зеркальные стекла.\r\n\r\nВ Комплекте: Чехол, Очки, Заменяемые Линзы.\r\n', 1490, 'y'),
(14, 'БРЮКИ ESDY LX-7', '/content/1621204569_product.jpg', 'Тактические брюки фирмы \"ESDY\" модель IX-7 stretch. (Стрейч)\r\nПлотный материал с содержанием эластана, немного тянется.\r\nАнатомический покрой не сковывающий движений.\r\n\r\nЦвет: Бежевый.\r\n\r\nРазмер: L.\r\n', 2490, 'y'),
(15, 'РУБАШКА 726ARMYFANS', '/content/1621204770_product.jpg', 'Основное назначение: оснащение спецподразделений и регулярных частей ВС, активный отдых на природе, рыбалка, охота, пешие походы;\r\n\r\nСезон: Весна, Лето, Осень от +15 С, до +25 С;\r\n\r\nОптимален при использовании в следующих целях:  \r\n• для повседневной носки;\r\n• в качестве камуфлированной туристической одежды;\r\n• в качестве военной формы;\r\n• для охоты и рыбалки;\r\n• для выполнения работ в помещениях и на улице;\r\n• для участия в военно-тактических и военно-спортивных играх (Страйкбол), (AIRSOFT), Лазертаг (Lasertag);\r\n• для активного отдыха на природе;\r\n• для пеших прогулок, походов различной степени сложности, в том числе горных походов;\r\n• для поездок на велосипеде;\r\n• для семейного отдыха на открытом воздухе;\r\n', 2190, 'y'),
(16, 'ПЕРЧАТКИ 5.11', '/content/1621204886_product.jpg', 'Цвет: чёрный и зелёный.', 690, 'y'),
(17, 'РЕМЕНЬ БРЮЧНОЙ 5.11', '/content/1621205047_product.jpg', 'Отличный ремень, который сгодится для любой ситуации. Он станет отличным дополнением гардероба любого мужчины.\r\n\r\nИзготовлен из прочной стропы жесткого плетения толщиной 4 мм. Металлическая пряжка отлично смотрится и надежно выполняет свои функции. Подойдет любителям стиля милитари в одежде.\r\n\r\nШирина ремня 4см, длина 130см.\r\n\r\nЦвет: бежевый, олива, песочный.\r\n', 590, 'y'),
(18, 'ТАКТИЧЕСКИЕ ПЕРЧАТКИ MECHANIX', '/content/1621205168_product.jpg', 'Размер Перчаток: M,L,XL.\r\n\r\nТактические перчатки Mechanix M-Pact Black - одна из самых популярных моделей от компании Mechanix Wear. Многолетний опыт Mechanix Wear позволяет разрабатывать и производить модели с высоким уровнем функциональности и надежной защиты кистей рук. Верхняя часть перчатки изготовлена из эластичного материала (спандекса). Фиксация на кисти обеспечивается липучкой из эластичной термостойкой резины. Большой и указательный палец усилен. Материал на ладони – прочная искусственная кожа (65% нейлон, 35% полиуретан).\r\n\r\nЦвет: Красный.', 1290, 'y'),
(19, 'ТЕРМОБЕЛЬЕ 5.11 ESDY', '/content/1621205416_product.jpg', 'Материал: 100% полиэстер\r\n\r\nОсобенности: плотно прилегает к телу и пропускает влагу, защищает от ветра. Термобельё 5.11 отлично подходит для повседневного ношения. Не натирающие плоские швы позволяют максимально комфортно использовать бельё, а анатомический крой не стесняет движений. В местах наибольшего отведения влаги расположены быстросохнущие вставки.\r\n\r\nТермобельё 5.11 изготовлено из износоустойчивого материала в состав которого входит спандекс, нейлон и полиэстер.\r\n', 2100, 'y'),
(20, 'ТАКТИЧЕСКИЕ ПЕРЧАТКИ MECHANIX БЕСПАЛЫЕ ', '/content/1621205520_product.jpg', 'Тактические перчатки Mechanix M-Pact Black - одна из самых популярных моделей от компании Mechanix Wear. Многолетний опыт Mechanix Wear позволяет разрабатывать и производить модели с высоким уровнем функциональности и надежной защиты кистей рук. Верхняя часть перчатки изготовлена из эластичного материала (спандекса). Фиксация на кисти обеспечивается липучкой из эластичной термостойкой резины. Материал на ладони – прочная искусственная кожа (65% нейлон, 35% полиуретан).\r\n\r\nРазмеры: М, L.\r\n', 800, 'y'),
(21, 'ПОЯСНАЯ ТАКТИЧЕСКАЯ СУМКА', '/content/1621205585_product.jpg', 'Размер: Длина 20 см\r\nВысота 15 см\r\nТолщина 5 см\r\nВес: 0.42 кг\r\n', 1190, 'y'),
(22, 'Скотч', '/content/1621453818_product.jpg', 'В продажу поступил специальный скотч защитного цвета. Он предназначен для оклейки любых частей ружья с целью маскировки и защиты от внешних повреждений, царапин и истирания. Состоит из нескольких слоёв. Очень прочный на разрыв и водонепроницаемый. Не оставляет после себя следов клея на поверхности. \r\n\r\nВ наличии имеются несколько видов раскраски: At-digital, woodland, desert, urban, oliv, cp, acu, hunterbrown. Ширина скотча 50 мм, длина - 4,5 метра, 5 метров\r\n\r\n- Цвет: Multicam\r\n- Цвет: Flecktarn', 600, 'y'),
(23, 'БЕЙСБОЛКА UNDER ARMOUR', '/content/1621205783_product.jpg', 'Имиджевые (пафосные) бейсболки. По сути представляют собой классические кепки с козырьком, изготовленные из легкого дышащего материала. На внешней части вышиты соответствующие надписи – рисунки. Предназначены для повседневного ношения в условиях жаркого климата или в летний период, т.к. отлично прикрывают глаза от прямых солнечных лучей. Тактические варианты бейсболок часто используют РМС (частные военные структуры), а также полицейские подразделения как часть униформы.\r\n\r\nПлюсы:\r\n- Удобная конструкция \r\n- Большой козырек\r\n- Для жаркого климата\r\n\r\nЦвет: Черная Олива \r\n', 650, 'y'),
(24, 'Куртка женская Verona, red', '/content/1621997359_product.jpg', 'Размер: L\r\n\r\nЯркий весенний тренд - женская куртка Verona. Если вы ведете активный образ жизни, занимаетесь спортом, а также любите пешие прогулки, куртка - **Verona это то, что вам нужно!** Стильная спортивная куртка отлично защитит Вас в любых погодных условиях. В составе изделия хлопок полиэстер 95%, спандекс 5%. Материал SoftSheel, из которого сделана куртка, сохраняет тепло, не промокает и дышит. Куртка прекрасно подойдет для любительниц экстрима и активного образа жизни. Комбинация материалов создает комфортный микроклимат, в куртке не будет жарко, ткань отлично отводит избыток тепла и пара от тела! Удобная и теплая куртка с карманом на молнии на груди и двумя боковыми карманами на молнии. Куртка прекрасно подойдет для занятий спортом на открытом воздухе: бег, легкая атлетика, беговые лыжи, велоспорт, футбол, туризм, альпинизм, активный отдых. **Verona - это** стильная защита от влаги и холода!\r\n\r\n• Состав: полиэстер 95%, спандекс 5%\r\n• Вид застежки: молния\r\n• Тип рукава: Длинные рукава\r\n• Покрой: приталенная по фигуре; Прямой покрой\r\n• Фактура материала: текстиль\r\n• Тип карманов: врезные\r\n• Материал подкладки: флис\r\n• Уход за вещами: Бережна стирка при температуре не более 30 гр\r\n• Особенности модели: Дышащая; защита от ветра и дождя; Защита от влаги и ветра; легкая в уходе; легкая и приятная ткань; легкая куртка; стильная одежда для улицы; стильная, трендовая модель, удобная, прктичная; удобная и практичная модель; Удобная и практичное ношение; удобно стирать; Удобство и качество; SOFTSHELL\r\n• Опции капюшона: несъемный\r\n• Длина изделия по спинке: 65 см\r\n• Рост модели на фото: 172 см\r\n• Параметры модели на фото (ОГ-ОТ-ОБ): 88-59-91\r\n• Размер на модели: S\r\n', 3100, 'y'),
(25, 'ТАКТИЧЕСКИЕ БРЮКИ', '/content/1621997441_product.jpg', 'Состав: 92% cotton, 8%polyester, coyote\r\nРазмер: M long \r\n', 2490, 'y'),
(26, 'РЮКЗАК Sling Bag, grey', '/content/1621997714_product.jpg', 'Сумка \"Sling Bag\" – профессиональный подход к экипировке, включающий совмещение практичности, функциональности и долговечности. Удобная фиксация сумки на корпусе производится за счет плечевого ремня, имеющего подкладку. Мягкий ремень с возможностью регулировки положения сумки обеспечивает максимально комфортное расположение изделия. Многочисленные карманы, которыми оснащена модель сумки\"Sling Bag\", позволяют удобно разместить все принадлежности и личные вещи.\r\n\r\n• Материал: Oxford 900D\r\n• Внутренний материал не трескается и не меняет цвет при длительном использовании.\r\n• Лямки прошиты изнутри закрепочным швом.\r\n', 2390, 'y'),
(27, 'Сумка через плечо большая, молния сбоку, coyote', '/content/1621997804_product.jpg', 'Тактическая сумка Слинг олицетворяет собой профессиональный подход к экипировке, включающий совмещение практичности, функциональности и долговечности. Удобная фиксация сумки на корпусе производится за счет плечевого ремня, имеющего подкладку. Мягкий ремень с возможностью регулировки положения сумки обеспечивает максимально комфортное расположение изделия. Многочисленные карманы, которыми оснащена сумка, позволяют удобно разместить все принадлежности и личные вещи.\r\n\r\nСостав: полиэстер 100%\r\nКоличество отделений: 3 шт.\r\nВид застежки: молния; фастекс\r\nФактура материала: текстильный\r\nНазначение ремня сумки: на плечо\r\nКарманы: Внутренний на молнии; Внутренний открытый; Для авторучки; Для мобильного телефона; Наружный спереди\r\nМатериал подкладки: полиэстер\r\nВес товара без упаковки (г): 600 г\r\nВысота предмета: 25 см\r\nГлубина предмета: 13 см\r\nШирина предмета: 22 см\r\nДлина плечевого ремня: 100 см\r\n', 1590, 'y'),
(28, 'Очки ESS Crossbow, black', '/content/1621997875_product.jpg', 'Копия очков ESS Crossbow. Идеальные очки для активных видов спорта и повседневной носки.\r\n• Линзы выполнены из качественных полимеров.\r\nХарактеристики:\r\n• В комплекте 3 линзы(темная, желтая, прозрачная).\r\n• Оправа.\r\n• Есть возможность установки увеличивающих линз для слабовидящих.\r\n• Чехол для хранения.\r\n• Сумка для переноски.\r\n• Маленький ремешок для закрепления очков.\r\n• Запасные дужки для носа.\r\n• Наклейка ESS.\r\n• Инструкция.\r\nПримечание:\r\n• Очки не баллистические. Максимально допустимая защита от частиц летящих со скоростью до 110 м/c.\r\nОписание:\r\n• Очки ESS Crossbow подходят для комфортной защиты глаз от УФ излучения и мелких частиц. Так же очки обладают достаточно стильным дизайном. Возможность установки дополнительных диоптрий. Специальный шнурок который не даст очкам упасть при активных видах спорта. Сменные линзы позволят выбрать оператору различные варианты расцветок линз. Отлично подойдут для сноубординга, лыжного спорта, велоспорта, вождения, скейтбординга, и других экстремальных видов спорта.\r\n', 1690, 'y'),
(29, 'Ремень тактический BLACKHAWK! широкий, черный', '/content/1621998168_product.jpg', '• Ремень застёгивается на специальный фастекс.\r\n• Регулируется при помощи широкой липучки велкро.\r\n• Очень удобно фиксируется.\r\n', 700, 'y'),
(30, 'Ремень оружейный двухточечный Y-169, coyote', '/content/1621998209_product.jpg', 'Качественный, надежный и прочный двухточечный оружейный ремень. С эластичными вставками для возможности амортизации оружия в случае необходимости. 2 крепких карабина.\r\nОсобенности:\r\n• Регулируемая длина ремня. Максимальная длина ремня – более 1.5 м.;\r\n• Надежные железные карабины;\r\n• Качественные материалы изготовления;\r\n• Может трансформироваться в одноточечный вариант.\r\n', 690, 'y'),
(31, 'Ремень оружейный одноточечный Y-174, olive', '/content/1621998278_product.jpg', 'Универсальный одноточечный ремень с удобным дизайном.\r\nОсобенности:\r\n• Материал изготовления – прочный, износостойкий нейлон;\r\n• Карабины изготовлены из метала и высокопрочного полимерного материала;\r\n• Длина ремня регулируется. Максимальная длина ~130 см.\r\n', 690, 'y'),
(32, 'Ремень \"Кобра\" black', '/content/1621998351_product.jpg', 'Армейский тактический ремень из прочного нейлона, используемого для изготовления снаряжения для страховочных работ. Идеально подходит для переноски подсумков с оружейными магазинами, кобуры с оружием и т.д. Регулируется по длине независимо от длины полотна. Надёжная фастекс пряжка Duraflex® из прочного ABS пластика. Ремень подходит для всех тактических брюк.\r\n• Состав: полиэстер 100%\r\n• Ширина: 3.8 см\r\n', 690, 'y'),
(33, 'Перчатки со вставкой, без пальцев, койот', '/content/1621998504_product.jpg', 'Отличные тактические перчатки созданы для работы с различным оружием, так же идеально подходят для страйкбола, пейтбола и практической стрельбы. Благодаря своим материалам они отводят влагу, тем самым их можно носить даже в жаркое время года.\r\n• Тактические беспалые перчатки хорошо защищают руку и не стесняют движений.\r\n• В конструкции перчаток применены пластиковые вставки на костяшках.\r\n• На запястье удобная застёжка-вэлкро - \"Дышащий материал\" и вентиляционные отверстия обеспечивают комфорт в любых погодных условиях.\r\n• Мягкие и удобные.\r\n• Обеспечивают защиту суставов.\r\n• Для жаркого климата.\r\nМатериал: 35% козьей кожи, 15% синтетической кожи, 13% спандекса, 10% нейлона, 10% неопрена, 5% резины, 5% карбона, 3% кевлара, 2% лайкры, 2% других материалов.\r\n\r\nРазмеры определяются по обхвату ладони.\r\nРАЗМЕР: XL, XXL\r\n', 700, 'y'),
(34, 'Подсумок под аптечку Molle CH-006, black', '/content/1621998634_product.jpg', '• Размеры: 18х14х6см. (высота/ширина/толщина)\r\n• Материал: Oxford 600.\r\n', 550, 'y'),
(35, 'Футболка Stalker, HDT FG', '/content/1621998943_product.jpg', 'Футболка имеет короткий рукав, прямой силуэт, круглый вырез горловины.\r\n• Ткань: 100%- х/б\r\n• Цвет: HDT FG\r\n• Размер: 52, 56\r\n', 390, 'y'),
(36, 'Очки защитные сетка (перфорация), olive', '/content/1621999003_product.jpg', 'Очки с защитной сеткой.\r\n• Выдерживают выстрел из страйкбольной винтовки со скоростью более 200 мс шаром 0,20 гр.\r\n• Материал: пластик, перфорированный металл.\r\n', 390, 'y'),
(37, 'Футболка Stalker, Kryptek typhon', '/content/1621999110_product.jpg', 'Футболка имеет короткий рукав, прямой силуэт, круглый вырез горловины.\r\n• Ткань: 100%- х/б\r\n• Цвет: Kryptek typhon\r\n• Размеры: 52, 54\r\n', 390, 'y'),
(38, 'Футболка Stalker, Olive', '/content/1621999189_product.jpg', 'Футболка имеет короткий рукав, прямой силуэт, круглый вырез горловины.\r\n• Ткань: 100%- х/б\r\n• Цвет: Olive\r\n• Размеры: 52, 54\r\n', 390, 'y'),
(39, 'Футболка Stalker, Coyote', '/content/1621999250_product.jpg', 'Футболка имеет короткий рукав, прямой силуэт, круглый вырез горловины.\r\n• Ткань: 100%- х/б\r\n• Цвет: Coyote\r\n• Размер: 54\r\n', 390, 'y'),
(40, 'Футболка Stalker, MTP', '/content/1621999366_product.jpg', 'Футболка имеет короткий рукав, прямой силуэт, круглый вырез горловины.\r\n• Ткань: 100%- х/б\r\n• Цвет: MTP\r\n• Размер: 54\r\n', 390, 'y'),
(41, 'Футболка Stalker, Тигр HDT FG', '/content/1621999432_product.jpg', 'Футболка имеет короткий рукав, прямой силуэт, круглый вырез горловины.\r\n• Ткань: 100%- х/б\r\n• Цвет: Тигр HDT FG\r\n• Размер: 52\r\n', 390, 'y'),
(42, 'Подсумок под пистолетный магазин на липучке, black', '/content/1621999635_product.jpg', 'Цвет: чёрный', 380, 'y');

-- --------------------------------------------------------

--
-- Структура таблицы `product_category`
--

CREATE TABLE `product_category` (
  `product_id` int NOT NULL,
  `category_id` int NOT NULL,
  `subcategory_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `product_category`
--

INSERT INTO `product_category` (`product_id`, `category_id`, `subcategory_id`) VALUES
(12, 12, 12),
(13, 13, 13),
(14, 14, 14),
(15, 15, 15),
(16, 13, 16),
(17, 13, 17),
(18, 13, 16),
(19, 16, 18),
(20, 13, 16),
(21, 13, 19),
(22, 13, 20),
(23, 12, 12),
(24, 17, 21),
(25, 14, 14),
(26, 13, 22),
(27, 13, 22),
(28, 13, 13),
(29, 13, 17),
(30, 13, 17),
(31, 13, 17),
(32, 13, 17),
(33, 13, 16),
(34, 13, 23),
(35, 18, 24),
(36, 13, 13),
(37, 18, 24),
(38, 18, 25),
(39, 18, 25),
(40, 18, 24),
(41, 18, 24),
(42, 13, 23);

-- --------------------------------------------------------

--
-- Структура таблицы `product_photo`
--

CREATE TABLE `product_photo` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `path` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `product_photo`
--

INSERT INTO `product_photo` (`id`, `product_id`, `path`) VALUES
(3, 12, '/content/12/1621203210_0.jpg'),
(4, 12, '/content/12/1621203210_1.jpg'),
(5, 14, '/content/14/1621204569_0.jpg'),
(6, 14, '/content/14/1621204569_1.jpg'),
(7, 15, '/content/15/1621204770_0.jpg'),
(8, 15, '/content/15/1621204770_1.jpg'),
(9, 16, '/content/16/1621204886_0.jpg'),
(10, 16, '/content/16/1621204886_1.jpg'),
(11, 17, '/content/17/1621205047_0.jpg'),
(12, 17, '/content/17/1621205047_1.jpg'),
(13, 17, '/content/17/1621205047_2.jpg'),
(14, 18, '/content/18/1621205169_0.jpg'),
(15, 19, '/content/19/1621205416_0.jpg'),
(16, 20, '/content/20/1621205520_0.jpg'),
(17, 23, '/content/23/1621453963_0.jpg'),
(18, 23, '/content/23/1621453963_1.jpg'),
(19, 24, '/content/24/1621997359_0.jpg'),
(20, 24, '/content/24/1621997359_1.jpg'),
(21, 26, '/content/26/1621997714_0.jpg'),
(22, 26, '/content/26/1621997714_1.jpg'),
(23, 26, '/content/26/1621997714_2.jpg'),
(24, 27, '/content/27/1621997804_0.jpg'),
(25, 27, '/content/27/1621997804_1.jpg'),
(26, 31, '/content/31/1621998278_0.jpg'),
(27, 31, '/content/31/1621998278_1.jpg'),
(28, 32, '/content/32/1621998351_0.jpg'),
(29, 32, '/content/32/1621998351_1.jpg'),
(30, 33, '/content/33/1621998505_0.jpg'),
(31, 33, '/content/33/1621998505_1.jpg'),
(32, 33, '/content/33/1621998505_2.jpg'),
(33, 34, '/content/34/1621998634_0.jpg'),
(34, 34, '/content/34/1621998634_1.jpg'),
(35, 34, '/content/34/1621998734_0.jpg'),
(36, 34, '/content/34/1621998781_0.jpg'),
(37, 35, '/content/35/1621998943_0.jpg'),
(38, 36, '/content/36/1621999003_0.jpg'),
(39, 38, '/content/38/1621999189_0.jpg'),
(40, 39, '/content/39/1621999250_0.jpg'),
(41, 40, '/content/40/1621999366_0.jpg'),
(42, 40, '/content/40/1621999366_1.jpg'),
(43, 40, '/content/40/1621999366_2.jpg'),
(44, 42, '/content/42/1621999636_0.jpg'),
(45, 42, '/content/42/1621999636_1.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `promocode`
--

CREATE TABLE `promocode` (
  `id` int NOT NULL,
  `code` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `percent` int NOT NULL,
  `active` enum('y','n') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `promocode`
--

INSERT INTO `promocode` (`id`, `code`, `percent`, `active`) VALUES
(1, 'CLIENT', 1, 'y'),
(2, 'SHERLOCK', 15, 'n');

-- --------------------------------------------------------

--
-- Структура таблицы `subcategory`
--

CREATE TABLE `subcategory` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `subcategory`
--

INSERT INTO `subcategory` (`id`, `category_id`, `name`) VALUES
(12, 12, 'Кепки'),
(13, 13, 'Очки'),
(14, 14, 'Тактические'),
(15, 15, 'Тактические'),
(16, 13, 'Перчатки'),
(17, 13, 'Ремни'),
(18, 16, 'Костюм'),
(19, 13, 'Поясная сумка'),
(20, 13, 'Прочее'),
(21, 17, 'Куртки'),
(22, 13, 'Сумка'),
(23, 13, 'Подсумок'),
(24, 18, 'Камуфляж '),
(25, 18, 'Однотонные');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `account_session`
--
ALTER TABLE `account_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `delivery_service`
--
ALTER TABLE `delivery_service`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_service_id` (`delivery_service_id`),
  ADD KEY `promocode_id` (`promocode_id`);

--
-- Индексы таблицы `orders_check`
--
ALTER TABLE `orders_check`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_id` (`orders_id`);

--
-- Индексы таблицы `orders_product`
--
ALTER TABLE `orders_product`
  ADD KEY `orders_id` (`orders_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product_category`
--
ALTER TABLE `product_category`
  ADD KEY `category_id` (`category_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Индексы таблицы `product_photo`
--
ALTER TABLE `product_photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_photo_ibfk_1` (`product_id`);

--
-- Индексы таблицы `promocode`
--
ALTER TABLE `promocode`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `account`
--
ALTER TABLE `account`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `account_session`
--
ALTER TABLE `account_session`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `delivery_service`
--
ALTER TABLE `delivery_service`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `orders_check`
--
ALTER TABLE `orders_check`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT для таблицы `product_photo`
--
ALTER TABLE `product_photo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT для таблицы `promocode`
--
ALTER TABLE `promocode`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `account_session`
--
ALTER TABLE `account_session`
  ADD CONSTRAINT `account_session_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`delivery_service_id`) REFERENCES `delivery_service` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`promocode_id`) REFERENCES `promocode` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `orders_check`
--
ALTER TABLE `orders_check`
  ADD CONSTRAINT `orders_check_ibfk_1` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `orders_product`
--
ALTER TABLE `orders_product`
  ADD CONSTRAINT `orders_product_ibfk_1` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orders_product_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `product_category_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `product_category_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `product_category_ibfk_3` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategory` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `product_photo`
--
ALTER TABLE `product_photo`
  ADD CONSTRAINT `product_photo_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `subcategory`
--
ALTER TABLE `subcategory`
  ADD CONSTRAINT `subcategory_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
