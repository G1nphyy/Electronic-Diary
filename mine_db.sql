-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sty 04, 2025 at 07:42 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mine_db`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `lekcja` text NOT NULL,
  `nalezy_id_szkoly` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `date`, `status`, `lekcja`, `nalezy_id_szkoly`) VALUES
(1, 19, '2024-12-30 07:10 - 07:55', 'Obency', 'Matematyka', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ogloszenia`
--

CREATE TABLE `ogloszenia` (
  `id` int(11) NOT NULL,
  `tytul` text NOT NULL,
  `tresc` text NOT NULL,
  `id_autora` int(11) NOT NULL,
  `data` text NOT NULL,
  `zdjecie_header` text NOT NULL,
  `zdjecia` text NOT NULL,
  `is_popular` tinyint(1) NOT NULL,
  `is_edited` tinyint(1) NOT NULL,
  `data_edited` text NOT NULL,
  `nalezy_id_szkoly` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `ogloszenia`
--

INSERT INTO `ogloszenia` (`id`, `tytul`, `tresc`, `id_autora`, `data`, `zdjecie_header`, `zdjecia`, `is_popular`, `is_edited`, `data_edited`, `nalezy_id_szkoly`) VALUES
(1, 'Witaj Świecie', 'Hello world (z ang. „Witaj, świecie”) – program, którego jedynym celem jest wypisanie na standardowym wyjściu napisu „Hello World!” lub innego prostego komunikatu.\r\n\r\nProgram taki ma na celu jedynie demonstrację języka, środowiska bądź biblioteki, w której był napisany. Nazwą tą określa się też wszystkie inne trywialne programy, dla których jedynym celem istnienia jest demonstrowanie sposobów programowania.\r\n\r\nProgramiści, ucząc się danego języka lub zaczynając naukę programowania, często piszą samodzielnie Hello world jako swój pierwszy program. Aktualnie w środowiskach informatycznych oraz w wielu kursach jest on swego rodzaju tradycyjnym elementem.\r\nHistoria\r\n\r\nPierwszy udokumentowany program Hello world opublikował Brian Kernighan w podręczniku do języka B z 1972 roku[1]. Był to kod, który miał ilustrować wykorzystanie zmiennych globalnych[2]:\r\n\r\nmain( ) {\r\n extrn a,b,c;\r\n putchar(a); putchar(b); putchar(c); putchar(\'!*n\');\r\n}\r\n\r\na \'hell\';\r\nb \'o, w\';\r\nc \'orld\';\r\n\r\nInspiracją dla wyświetlenia akurat takiego komunikatu była dla B. Kernighana kreskówka, w której wykluwający się z jajka kurczak wypowiadał słowa Hello, world![1].\r\n\r\nTen przykład został zaadaptowany do języka C i opublikowany w 1974 roku w publikacji Programming in C: A Tutorial. Kod wypisuje komunikat hello world[3] (bez wielkich liter i wykrzyknika):\r\n\r\nmain()\r\n{\r\n    printf(\"hello, world\");\r\n}\r\n\r\nedytowany:\r\npatrz na autora 👇🏿\r\n', 4, '2024-12-30 02:28:40', 'uploads/1735522120_header_851557_641022965913315_1865053205_n.png', '[\"uploads\\/1735522120_gallery_ZSK_logo_edited.png\",\"uploads\\/1735522120_gallery_images.png\",\"uploads\\/1735522120_gallery_587e32329686194a55adab75.png\",\"uploads\\/1735522120_gallery_Pusheen_the_Cat.png\"]', 1, 1, '2024-12-30 02:29:47', 0),
(2, 'Witaj szkoło', 'Kawa – napój sporządzany z palonych, a następnie zmielonych lub poddanych instantyzacji ziaren kawowca, zwykle podawany na gorąco. Pochodzi z Etiopii, w Europie pojawiła się około XVI wieku. Jedna z najpopularniejszych używek na świecie i główne źródło kofeiny.\r\nNazwa\r\n\r\nNazwa kawy pochodzi prawdopodobnie od arabskiego kahwa. Do większości języków europejskich przeniknęła poprzez tureckie słowo kahve i utworzoną od niego włoską nazwę caffè. Drugą prawdopodobną etymologią nazwy kawa jest nazwa etiopskiego miasta Kaffa, dziś – Kefa.\r\n Osobny artykuł: Etiopski ceremoniał parzenia kawy.\r\n\r\nW Polsce pojawiła się po bitwie pod Wiedniem w 1683 w formie tureckiej. Wcześniej znana była regionalnie, np. w Kamieńcu Podolskim, gdzie dla wojsk osmańskich otwarto po zajęciu przez nie miasta kawiarnie, polscy mieszkańcy Kamieńca mogli wówczas zapoznać się z kawą i kawiarnią. Dokładna etymologia wyrazu nie jest znana. Kahva oznacza po arabsku zarówno kawę, jak i wino, a drugie znaczenie jest starsze. Nazwa może wywodzić się od znanego z uprawy kawy regionu Kaffa w Etiopii. Może ona pochodzić także od słowa kohwet oznaczającego siłę.\r\nHistoria\r\nPalone ziarna kawy\r\nKawa w postaci zliofilizowanej\r\nPianka kawowa\r\nRęczny młynek do kawy\r\nEkspres do kawy\r\nProdukcja kawy w Indonezji\r\nPraca Tadeusza Krusińskiego na temat parzenia kawy. Wydrukowana w 1769 w drukarni Korpusu Kadetów.\r\nOdkrycie kawy\r\n\r\nOwoce kawowca wykorzystywane były w Etiopii już w I tysiącleciu p.n.e. Spożywano je gotowane z dodatkiem masła i soli, lecz nie uprawiano, tylko zbierano ze stanowisk naturalnych. Prawdopodobnie kawę odkrył lud Oromo, zamieszkujący etiopski region Kaffa. Przypuszczalnie w XIII lub XIV wieku przywiezione zostały przez kupców arabskich do Jemenu, który do dziś dostarcza najdroższą kawę. Prawdopodobnie tam opracowano metodę preparowania nasion przez prażenie i wytwarzanie z nich napoju, który Beduini rozpowszechnili w całej Arabii.\r\n\r\nZ odkryciem kawy wiążą się dwie legendy:\r\n\r\n    pierwsza przypisuje związane z tym zasługi sufiemu Szejchowi asz-Szadhiliemu. Miał on w czasie swojej wędrówki po Afryce przypadkiem natrafić na stado wyjątkowo pobudzonych kóz. Z ciekawości spróbował zjadanych przez nie owoców, poznając ich niezwykły wpływ na organizm człowieka;\r\n    druga, bardzo podobna historia jako odkrywcę podaje etiopskiego pasterza o imieniu Kaldi.\r\n\r\nNowa używka na Bliskim Wschodzie\r\n\r\nJuż w końcu XV wieku położony na południowym krańcu Morza Czerwonego arabski port Al-Mucha (bardziej znany jako Mokka) był dużym ośrodkiem handlu ziarnami rośliny zwanej kohwet. Wraz z ekspansją arabską zwyczaj picia kawy rozpowszechnił się na całym Bliskim Wschodzie. Początkowo budził on wiele nieufności, czy wręcz niechęci. Spożycie kawy zostało zabronione w Mekce w roku 1511, a w Kairze w 1532. Wobec szybkiego wzrostu popularności napoju zakazy wkrótce zniesiono. W 1554 w Stambule otwarto pierwszą kawiarnię. O ile w Arabii kontrowersje wokół nowej używki szybko zanikły, o tyle nad Bosforem jej kariera rozwijała się znacznie wolniej. Pobudzające właściwości palonych ziaren bywały obiektem krytyki religijnych ortodoksów, a pierwsze kawiarnie stawały się nieraz forami burzliwych dyskusji, co niepokoiło sułtanów. Restrykcje ustały u schyłku XVI wieku. Zarazem Imperium Osmańskie jako pośrednik w handlu stało się mocarstwem kawowym.\r\nKawa w Europie i Ameryce\r\n\r\nJako pierwszy Europejczyk kawę opisał niemiecki botanik i podróżnik Leonhard Rauwolf. W 1573 roku rozpoczął on trzyletnią podróż po Bliskim Wschodzie. Celem eskapady było odkrycie nowych ziół i lekarstw dla kompanii kupieckiej jego szwagra, Melchiora Manlicha. Rauwolf powrócił z wieloma cennymi towarami, jak również obfitymi zapiskami dotyczącymi tureckich zwyczajów. Na temat kawy wyraził się w następujący sposób:\r\n\r\nBardzo dobry napój zwany przez nich „Chaube”, który jest niemal tak czarny jak inkaust i bardzo dobry na dolegliwości, szczególnie żołądkowe. Spożywają go oni o poranku, w otwartych miejscach, przed wszystkimi i bez najmniejszej oznaki strachu czy ostrożności. Napój popijają małymi łyczkami, tak ciepły jak to tylko możliwe, z glinianych i porcelanowych kubków.\r\n\r\nDuże znaczenie dla rozpowszechnienia wiedzy o używce miały również zapiski włoskiego botanika i lekarza Prospera d’Alpino. Towarzyszył on weneckiemu poselstwu do Egiptu i po powrocie opisał lecznicze działanie kawy. Prawdopodobnie to dzięki niemu nazwa „caffè” przeniknęła do większości języków zachodniej Europy (ang. coffee, fr. café).\r\n\r\nTrudno określić dokładnie kiedy kawa po raz pierwszy trafiła do Europy. W oparciu o zapiski Leonarda Rauwulfa z 1583 roku można stwierdzić, że stała się ona dostępna jeszcze w XVI wieku. Jej import był zasługą dwóch kompanii wschodnioindyjskich: brytyjskiej i holenderskiej. Pierwszą kawiarnię w Anglii otwarto w Oksfordzie w roku 1650. Należała ona do osmańskiego Żyda imieniem Jakub (ang. Jacob lub Jacobs). W Londynie podobny przybytek rozpoczął działalność dwa lata później przy alei świętego Michała.\r\n\r\nNa kontynencie kawa rozpowszechniła się ze sporym opóźnieniem. Pierwszy raz sprowadzono ją do Francji prawdopodobnie dopiero w roku 1644. W 1669 roku napój poznały elity paryskie na przyjęciu wydanym przez posła osmańskiego Mustafę Paszę. Pierwszy kafehaus w stolicy kraju otwarto w 1671 roku. W tym czasie w Anglii działało już ponad 3000 kawiarni. Napój zyskiwał także rosnącą popularność za oceanem. W roku 1670 rozpoczęła działalność pierwsza kawiarnia w Bostonie.\r\n\r\nPo zwycięstwie pod Wiedniem (1683) Jerzy Franciszek Kulczycki założył pierwszy kafehaus w stolicy Austrii. Legenda mówi, że zrobił to korzystając z zapasów kawy porzuconych przez uciekających Turków. Następnie pomógł on spopularyzować zwyczaj dodawania do napoju cukru i mleka. Do niedawna w Wiedniu istniała tradycja wywieszania portretu Kulczyckiego (niem. Kolschitzky) w oknach kawiarni. Ostatnio mówi się, że pierwszym właścicielem kawiarni w Imperium Habsburgów był jednak Ormianin Johannes Diodato[1][2].\r\n\r\nPierwszą kawiarnią w Salzburgu była istniejąca do dziś Café Tomaselli. Kawiarnia ta jest najstarszą do dziś istniejącą kawiarnią Europy Zachodniej. Bywali w niej: Wolfgang Amadeus Mozart, Michael Haydn, Hugo von Hofmannsthal i Max Reinhardt.\r\n\r\nNowa używka budziła na starym kontynencie wiele kontrowersji. Była produktem pochodzenia arabskiego, niektórzy widzieli więc w niej dzieło szatana. Na początku XVII wieku na temat kawy wypowiedział się sam papież Klemens VIII, za którego przyzwoleniem napój ten wkroczył do świata chrześcijańskiego. W XVII i XVIII w. wielkie potęgi kolonialne rozpoczęły uprawę kawowca w swoich koloniach. Holandia w 1658 roku założyła pierwsze plantacje na Cejlonie. Następnie rozszerzyła uprawę na Jawę, z której rozprzestrzeniła się ona na cały Archipelag Sundajski. Francja pierwsze plantacje założyła na Martynice. Wkrótce uprawę kawy rozpoczęto również w Gujanie Francuskiej.\r\n\r\nW roku 1719 Portugalczycy wykradli z Gujany sadzonki i założyli pierwsze plantacje w Brazylii. Dzięki nim do roku 1800 kawa zmieniła się z używki dla elit w ogólnodostępny napój dla każdego. Przez cały wiek XIX i pierwsze dekady XX wieku Brazylia pozostawała głównym producentem i niemal monopolistą na rynku kawy. Dopiero w kolejnych latach polityka utrzymywania wysokich cen otworzyła drzwi dla kolejnych państw: Kolumbii, Gwatemali i Indonezji.\r\n\r\nPod koniec XIX wieku Wiedeń posiadał ponad 1200 kawiarni.\r\n\r\nObecnie kawa jest jednym z najpopularniejszych na świecie napojów. Wypija jej się około 400 mld filiżanek rocznie.\r\nKawa w Polsce\r\n\r\nDo Polski kawa dotarła pod koniec XVII wieku z południa, od panujących nad Mołdawią Turków. W 1670 w elegii do kuzyna Stanisława Morsztyna pierwszy wymienił kawę Jan Andrzej Morsztyn we fragmencie \"W Malcieśmy pomnę kosztowali kafy, Trunku dla baszów (...)\". Trunek ten nie przypadł jednak poecie do gustu (\"Napój tak brzydka trucizna i jady, co żadnej śliny nie puszcza przez zęby\")[1]. Wiadomo, że jej miłośnikami byli Jan III Sobieski oraz Bohdan Chmielnicki, powszechnie jednak nowy napój uznawano za niesmaczny, a nawet szkodliwy. Potępiali go m.in. Wacław Potocki oraz Jan Andrzej Morsztyn. Kawa zyskała większą popularność dopiero na przełomie XVII i XVIII wieku. Najpierw moda na jej spożycie ogarnęła Gdańsk, gdzie powstały pierwsze kawiarnie zwane kafehausami. Następnie rozpowszechniła się na resztę kraju. Już na początku XVIII wieku znalazła się w podręcznikach medycznych jako specyfik na schorzenia przewodu pokarmowego. Początkowo kawa była napojem elitarnym, ale od XVIII w. stała się bardziej popularna i łatwiej dostępna. Propagowały ją czasopisma „Monitor” i „Patriota Polski”, zapewniając, że nowa używka nie szkodzi zdrowiu i nie pozbawia zdolności do pracy. W 1769 Józef Epifani Minasowicz przyczynił się do opublikowania w Warszawie \"Pragmatographia de legitymo usu ambrozyi tureckiei, to jest: Opisanie sposobu należytego zażywania kawy tureckiej (...)\" pierwszej monografii na temat kawy w języku polskim, którą napisał jezuicki misjonarz i orientalista Tadeusz Krusiński[3]. Jędrzej Kitowicz w Opisie obyczajów za panowania Augusta III opisał rozpowszechnienie się zwyczaju picia kawy, popularnej nawet wśród rzemieślników i zamożnego chłopstwa[4].\r\n\r\nMimo protestów przeciwników, kawa zyskiwała sobie coraz większe grono zwolenników. Uwielbiał ją Ignacy Krasicki, a Adam Kazimierz Czartoryski napisał nawet komedię „Kawa” (1779)[1]. W 1795 roku ukazał się pierwszy w języku polskim podręcznik dla kawiarzy „Krótka wiadomość o kawie i jej właściwościach i skutkach na zdrowie ludzi spływających...” – przekład z jęz. francuskiego.\r\n\r\nNajchętniej kupowano sprowadzaną z Turcji de mocca. Gdy jej nie było, sięgano po ziarna z Lewantu, Martyniki, Indii Holenderskich. Spopularyzowanie się używki pozwoliło rozwinąć wytwórczość porcelany i fajansu w manufakturach w Białej Podlaskiej, Żółkwi czy Ćmielowie.\r\n\r\nPoczątkowo kawę pito na wzór wschodni, czyli bez żadnych dodatków. Szybko zaczęto jednak dodawać m.in. mleko, słodką śmietanę, cukier, a nawet sól. Pod koniec XVIII wieku mocna kawa „po polsku”, pita z wyborową tłustą śmietanką, wśród cudzoziemców dorównywała sławą naszemu chlebowi. Dla odróżnienia kawę słabą zwano „niemiecką” lub „śląską”. Czarną kawę pijano już tylko w czasie postów celem umartwiania się.\r\n\r\nW drugiej połowie XVIII wieku z Prus przyszła praktyka mieszania kawy z cykorią. W roku 1818 Ferdynard Bohm założył we Włocławku pierwszą wytwórnię kawy zbożowej. W użyciu, szczególnie wśród ludzi niezamożnych były też inne jej substytuty wytwarzane m.in. z bobu, żołędzi lub palonego grochu.\r\n\r\nWielką karierę w miastach zrobiły kawy lub kafehausy, z czasem nazwane kawiarniami. Na początku XVIII wieku Antoni Momber założył sławną później kawiarnię w Gdańsku, a w roku 1724 Francuz Henri Duval otworzył lokal w Warszawie. W połowie XVIII wieku w stolicy istniało już kilka kafenhausów m.in. za Żelazną Bramą Ogrodu Saskiego, naprzeciw studni przy Starym Rynku od Krzywego Koła i inne. Najbardziej znany był kafenhaus Okuniowej gdzie spotykali się członkowie Kuźnicy Kołłątajowskiej[1]. Życie kawiarniane nabierało tempa. W 1822 roku w Warszawie działały 122, a w 1844 aż 180 kawiarni. W Krakowie w połowie stulecia naliczono 55 lokali, które rywalizowały z zachowującymi odrębność cukierniami. W XIX wieku kawiarnie przekształciły się na lokale o charakterze klubowym. Istniał, szczególnie w różnego rodzaju karczmach, obyczaj wzmacniania kawy alkoholem.\r\n\r\nOdmienny obyczaj spożycia kawy wykształcił się wśród ziemiaństwa. Zamożna szlachta zatrudniała wykwalifikowaną służbę (tzw. „kawiarkę”), której zadaniem było parzenie kawy. Za idealny napój w XIX wieku uznawano ten mający „czarność węgla, przejrzystość bursztynu, zapach mokki i gęstość miodowego płynu”[5]. Kawę podawano rankiem do łóżka państwa „na rozbudzenie”. Pijano ją także dla smaku podczas podwieczorku.\r\n\r\nHandlem kawą na ziemiach polskich zajmowali się początkowo kupcy obracającymi wszelkimi towarami. Firmy specjalizujące się w obrocie kawą pojawiły się około połowy XIX wieku w Galicji. Aż do końca XIX wieku sprzedawano kawę w postaci surowej. Palić należało ją samemu. Służyły temu skomplikowane maszynki do kawy, będące obowiązkowym wyposażeniem mieszczańskiego, bądź inteligenckiego domu. Ponieważ efekt nie zawsze był dobry, lepiej było wybrać się do kawiarni. Przełom nastąpił w roku 1882, gdy Tadeusz Tarasiewicz, właściciel firmy „Pluton”, zaoferował kawę paloną we własnej palarni.\r\n\r\nW odrodzonej Polsce wśród firm handlujących kawą największe znaczenie miały: Pluton, Spółdzielnia Spożywców Społem, Wielkopolski Skład Kawy, a także Julius Meinl oraz warszawskie przedsiębiorstwa Józefa Fettera i Alfreda Jurzykowskiego. Z powodu kryzysu gospodarczego firmy powołały Zrzeszenie Importerów Kawy i Herbaty RP, a następnie Kompanię Handlu Zamorskiego.\r\n\r\nPodczas II wojny światowej powróciły dawne sposoby wytwarzania kawy domowymi sposobami. Otrzymywano ją przede wszystkim z żołędzi palonych na płycie kuchennej. Niedobory kawy nie zniknęły w okresie PRL-u, stając się ważnym problemem społecznym. Dopiero w 1964 roku udało się przekroczyć wysokość jej przedwojennego importu. Kawa stała się niezbędnym elementem życia biurowego, szczególnie po wprowadzeniu w latach 60. w Warszawie przepisu o rozpoczynaniu pracy o godzinie szóstej. W efekcie używka zaczęła stanowić jeden z najpowszechniejszych rodzajów łapówek. Importowane ziarna bezskutecznie próbowano zastąpić kawą zbożową rodzimej produkcji („Inka”). Spadkiem po PRL-u stał się między innymi zwyczaj podawania kawy w szklankach, a nie filiżankach. Obecnie, zwłaszcza wśród młodego pokolenia upowszechnił się zwyczaj picia kawy z ceramicznych kubków.\r\n\r\nWraz z wprowadzeniem gospodarki wolnorynkowej zniknął problem niedoborów kawy, która ponownie stała się napojem ogólnodostępnym. ', 4, '2024-12-30 02:41:39', 'uploads/1735522899_header_0nMuNdKX.jpg', '[\"uploads\\/1735522899_gallery_dTQJTmFR.jpg\",\"uploads\\/1735522899_gallery_rEt8JmfG.jpg\"]', 0, 0, '', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `plany lekcji`
--

CREATE TABLE `plany lekcji` (
  `id` int(11) NOT NULL,
  `Klasa` text NOT NULL,
  `Poniedzialek` text DEFAULT NULL,
  `Wtorek` text DEFAULT NULL,
  `Sroda` text DEFAULT NULL,
  `Czwartek` text DEFAULT NULL,
  `Piatek` text DEFAULT NULL,
  `nalezy_id_szkoly` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `schools`
--

CREATE TABLE `schools` (
  `Id_szkoly` int(11) NOT NULL,
  `nazwa_szkoly` text NOT NULL,
  `adres_szkoly` text NOT NULL,
  `dyrektor_szkoly` text NOT NULL,
  `zastepca_dyrektora_szkoly` text DEFAULT NULL,
  `kod_szkoly` text NOT NULL,
  `pedagog` text DEFAULT NULL,
  `psyhiatra` text DEFAULT NULL,
  `lekarz` text DEFAULT NULL,
  `data_dolaczenia` text NOT NULL,
  `typ_szkoly` text DEFAULT NULL,
  `status_public_private` text DEFAULT NULL,
  `internat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`Id_szkoly`, `nazwa_szkoly`, `adres_szkoly`, `dyrektor_szkoly`, `zastepca_dyrektora_szkoly`, `kod_szkoly`, `pedagog`, `psyhiatra`, `lekarz`, `data_dolaczenia`, `typ_szkoly`, `status_public_private`, `internat`) VALUES
(1, 'Nazwa Szkoły', 'Adres szkoły', 'Dyrektor szkoły', 'Zastępca dyrektora szkoły', 'Kod szkoły', 'Pedagog', 'Psychiatra', 'Lekarz', '1972-04-24', 'Typ szkoły', 'Status', 'Internat');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `klasa` text NOT NULL,
  `przedmiot` text NOT NULL,
  `lekcja` int(11) NOT NULL,
  `kategoria` text NOT NULL,
  `nazwa` text NOT NULL,
  `opis` text NOT NULL,
  `data_utworzenia` date NOT NULL,
  `data` date NOT NULL,
  `nalezy_id_szkoly` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Imie` text NOT NULL,
  `Nazwisko` text NOT NULL,
  `Klasa` text DEFAULT NULL,
  `E-mail` text NOT NULL,
  `Haslo` text NOT NULL,
  `Rola` text NOT NULL,
  `Czego_uczy` text DEFAULT NULL,
  `icon` text NOT NULL,
  `nalezy_id_szkoly` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Imie`, `Nazwisko`, `Klasa`, `E-mail`, `Haslo`, `Rola`, `Czego_uczy`, `icon`, `nalezy_id_szkoly`) VALUES
(2, 'Filip', 'Garczyk', NULL, 'filip@gmail.com', '$2y$10$/ln/KOT5Li5ogGBFzxo1KuyE/qTHkfgLQmb6ekqRZXfVjgm/eS/Au', 'Nauczyciel', 'Matematyka;Polski;', 'avatars/blue_avatar.png', '1'),
(4, 'Michał', 'Waligóra', NULL, 'michal@gmail.com', '$2y$10$5JZAotqJlvT6mT.xjmKlU.t8WCg2UFrdWZSMhwr6CoYM.gNZT1nU.', 'Admin_d', NULL, '', '17'),
(12, 'Adam', 'Szew', '1A', 'adam@gmail.com', '$2y$10$SukMZ2Ul.lcw9I.8hCxsj.nVYwFFlkeDH/11QQ8oOh3KMJNdqdZBO', 'Admin', NULL, '', '1'),
(19, 'Ada', 'Kurasz', '1A', 'alex@gmail.com', '$2y$10$JTBlFZyo1t.ziOVuc.4Ge.VyMr5zprXYvV66h67qB5UJPqmQEGNiW', 'Uczen', NULL, '', '1');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_oceny`
--

CREATE TABLE `users_oceny` (
  `id_ocen` int(11) NOT NULL,
  `id_ucznia` int(11) NOT NULL,
  `Matematyka` text DEFAULT NULL,
  `Angielski` text DEFAULT NULL,
  `Polski` text DEFAULT NULL,
  `Systemy Operacyjne` text DEFAULT NULL,
  `nalezy_id_szkoly` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `users_oceny`
--

INSERT INTO `users_oceny` (`id_ocen`, `id_ucznia`, `Matematyka`, `Angielski`, `Polski`, `Systemy Operacyjne`, `nalezy_id_szkoly`) VALUES
(2, 2, '', NULL, NULL, NULL, ''),
(4, 4, NULL, NULL, NULL, NULL, ''),
(9, 12, NULL, NULL, NULL, NULL, '14'),
(10, 19, '1$5$Sprawdzian - Dział 3', '6$1$Sprawdzian - Unit 3', '4$2$a', '3$2$1', '17');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wiadomości`
--

CREATE TABLE `wiadomości` (
  `id` int(11) NOT NULL,
  `id_od` int(11) NOT NULL,
  `id_do` int(11) NOT NULL,
  `data` text NOT NULL,
  `odczytane` tinyint(1) NOT NULL,
  `tytul` text NOT NULL,
  `tresc` longtext NOT NULL,
  `nalezy_id_szkoly` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `wiadomości`
--

INSERT INTO `wiadomości` (`id`, `id_od`, `id_do`, `data`, `odczytane`, `tytul`, `tresc`, `nalezy_id_szkoly`) VALUES
(1, 4, 12, '2024-12-30 02:34:43', 0, 'Witaj świecie', '<b>Witaj</b> użytkowniku.. ', 0),
(2, 4, 19, '2024-12-30 02:35:04', 0, 'Witaj świecie', '<b>Witaj </b>użytkowniku<br>', 0),
(3, 4, 2, '2024-12-30 02:35:45', 0, 'Witaj świecie', '<b>Witaj</b> użytkowniku', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zmiany_plan_lekcji`
--

CREATE TABLE `zmiany_plan_lekcji` (
  `id` int(11) NOT NULL,
  `rodzaj` text NOT NULL,
  `data` text NOT NULL,
  `klasa` text NOT NULL,
  `co_sie_dzieje` text NOT NULL,
  `nalezy_id_szkoly` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `ogloszenia`
--
ALTER TABLE `ogloszenia`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `plany lekcji`
--
ALTER TABLE `plany lekcji`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`Id_szkoly`);

--
-- Indeksy dla tabeli `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users_oceny`
--
ALTER TABLE `users_oceny`
  ADD PRIMARY KEY (`id_ocen`);

--
-- Indeksy dla tabeli `wiadomości`
--
ALTER TABLE `wiadomości`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zmiany_plan_lekcji`
--
ALTER TABLE `zmiany_plan_lekcji`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ogloszenia`
--
ALTER TABLE `ogloszenia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plany lekcji`
--
ALTER TABLE `plany lekcji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `Id_szkoly` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users_oceny`
--
ALTER TABLE `users_oceny`
  MODIFY `id_ocen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `wiadomości`
--
ALTER TABLE `wiadomości`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `zmiany_plan_lekcji`
--
ALTER TABLE `zmiany_plan_lekcji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
