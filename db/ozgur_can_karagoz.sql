-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 14 May 2018, 13:34:07
-- Sunucu sürümü: 10.1.31-MariaDB
-- PHP Sürümü: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `fabrika_yonetim`
--

DELIMITER $$
--
-- Yordamlar
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `demirbas tipindeki demirbas sayisini guncelle` (IN `newDemirBasTipi` INT)  NO SQL
BEGIN
IF (newDemirBasTipi IS NOT NULL) THEN
UPDATE demirbas_tipi SET tipteki_demirbas_sayisi = (SELECT COUNT(*) FROM demirbas WHERE demirbas_tipi = newDemirBasTipi) WHERE id = newDemirBasTipi;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `demirbas tipine gore demirebas sayilari` ()  NO SQL
Select demirbas_tipi.isim, Count(*) From demirbas, demirbas_tipi where demirbas.demirbas_tipi = demirbas_tipi.id group by demirbas_tipi$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `havalandirma tipindeki demirbaslarin konumlarindaki sensorler` ()  NO SQL
Select sensor.isim, sensor_tipi.ad From sensor, sensor_tipi Where sensor.sensor_tipi = sensor_tipi.id and sensor.konum IN (Select konum From demirbas Where demirbas_tipi = (Select id from demirbas_tipi Where demirbas_tipi.isim = 'Havalandırma' ))$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ismi girilen demirbasin son hareketi` (IN `demirbasIsim` VARCHAR(200))  NO SQL
Select demirbas.isim, hareket.hareket_turu, MAX(hareket.hareket_zamani) From hareket, demirbas Where hareket.demirbas_id = demirbas.id And demirbas.isim = demirbasIsim$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `konumdaki demirbaslari getir` (IN `konumIsım` VARCHAR(200))  NO SQL
Select demirbas.isim From demirbas, konum Where demirbas.konum = konum.id and konum.isim = konumIsım$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sensor tipine gore sensor sayilari` ()  NO SQL
Select sensor_tipi.ad, Count(*) From sensor, sensor_tipi where sensor.sensor_tipi = sensor_tipi.id group by sensor_tipi$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tarih araligindaki hareketleri getir` (IN `baslangic` DATETIME, IN `bitis` DATETIME)  NO SQL
Select demirbas.isim, hareket.hareket_turu, hareket.hareket_zamani From hareket, demirbas Where demirbas.id = hareket.demirbas_id and hareket_zamani > baslangic and hareket_zamani < bitis$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `demirbas`
--

CREATE TABLE `demirbas` (
  `id` int(11) NOT NULL,
  `isim` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `acik_kapali` tinyint(1) NOT NULL,
  `konum` int(11) NOT NULL,
  `demirbas_tipi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `demirbas`
--

INSERT INTO `demirbas` (`id`, `isim`, `acik_kapali`, `konum`, `demirbas_tipi`) VALUES
(1, 'montaj aydınlatma', 0, 1, 1),
(2, 'ambalaj aydınlatma', 0, 2, 1),
(3, 'idare aydınlatma', 0, 3, 1),
(4, 'depo aydınlatma', 0, 4, 1),
(5, 'yemekhane aydınlatma', 1, 5, 1),
(6, 'wc aydınlatma', 0, 6, 1),
(7, 'boya aydınlatma', 0, 7, 1),
(8, 'giriş aydınlatma', 0, 8, 1),
(9, 'montaj havalandırma', 0, 1, 2),
(10, 'ambalaj havalandırma', 0, 2, 2),
(11, 'idare havalandırma', 0, 3, 2),
(12, 'depo havalandırma', 0, 4, 2),
(13, 'yemekhane havalandırma', 0, 5, 2),
(14, 'wc havalandırma', 0, 6, 2),
(15, 'boya havalandırma', 0, 7, 2),
(16, 'giriş havalandırma', 0, 8, 2),
(17, 'giriş kapısı', 1, 8, 3),
(18, 'kapı önü tente', 0, 8, 3),
(19, 'dış kapı bariyer', 0, 8, 3),
(20, 'depo kepenk', 0, 4, 3);

--
-- Tetikleyiciler `demirbas`
--
DELIMITER $$
CREATE TRIGGER `demirbas_eklenince_sayiyi_guncelle` AFTER INSERT ON `demirbas` FOR EACH ROW CALL `demirbas tipindeki demirbas sayisini guncelle`(NEW.demirbas_tipi)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `demirbas_guncellenince_sayiyi_guncelle` AFTER UPDATE ON `demirbas` FOR EACH ROW BEGIN
CALL `demirbas tipindeki demirbas sayisini guncelle`(OLD.demirbas_tipi);
CALL `demirbas tipindeki demirbas sayisini guncelle`(NEW.demirbas_tipi);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `demirbas_silinince_sayiyi_guncelle` AFTER DELETE ON `demirbas` FOR EACH ROW CALL `demirbas tipindeki demirbas sayisini guncelle`(OLD.demirbas_tipi)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hareket_ekle` BEFORE UPDATE ON `demirbas` FOR EACH ROW BEGIN
IF (NEW.acik_kapali != OLD.acik_kapali) THEN
INSERT INTO hareket (demirbas_id, hareket_turu, hareket_zamani) VALUES(NEW.id, NEW.acik_kapali, NOW());
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `demirbas_tipi`
--

CREATE TABLE `demirbas_tipi` (
  `id` int(11) NOT NULL,
  `isim` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `tipteki_demirbas_sayisi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `demirbas_tipi`
--

INSERT INTO `demirbas_tipi` (`id`, `isim`, `tipteki_demirbas_sayisi`) VALUES
(1, 'Aydınlatma', 9),
(2, 'Havalandırma', 8),
(3, 'Bariyer ve Tenteler', 4);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hareket`
--

CREATE TABLE `hareket` (
  `id` int(11) NOT NULL,
  `demirbas_id` int(11) NOT NULL,
  `hareket_turu` tinyint(1) NOT NULL,
  `hareket_zamani` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `hareket`
--

INSERT INTO `hareket` (`id`, `demirbas_id`, `hareket_turu`, `hareket_zamani`) VALUES
(0, 17, 0, '2018-05-07 10:44:23'),
(0, 17, 1, '2018-05-07 10:44:30'),
(0, 5, 1, '2018-05-07 10:49:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `konum`
--

CREATE TABLE `konum` (
  `id` int(11) NOT NULL,
  `isim` varchar(100) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `konum`
--

INSERT INTO `konum` (`id`, `isim`) VALUES
(1, 'Montaj'),
(2, 'Ambalaj'),
(3, 'İdare'),
(4, 'Depo'),
(5, 'Yemekhane'),
(6, 'WC'),
(7, 'Boya'),
(8, 'Giriş'),
(9, 'Üretim'),
(10, 'Pazarlama');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sensor`
--

CREATE TABLE `sensor` (
  `id` int(11) NOT NULL,
  `isim` text COLLATE utf8_turkish_ci NOT NULL,
  `kritik_min` int(11) NOT NULL,
  `kritik_max` int(11) NOT NULL,
  `konum` int(11) NOT NULL,
  `sensor_tipi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `sensor`
--

INSERT INTO `sensor` (`id`, `isim`, `kritik_min`, `kritik_max`, `konum`, `sensor_tipi`) VALUES
(1, 'idare_sicaklik', -40, 40, 3, 1),
(2, 'boya_nem', 0, 100, 7, 2),
(3, 'giris_yagmur', 0, 1, 8, 3),
(4, 'wc_karbonmonoksit', 0, 300, 6, 4),
(5, 'yemekhane_mesafe', 0, 100, 5, 5),
(6, 'uretim_isik', 0, 5, 9, 6);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sensor_deger_gecmisi`
--

CREATE TABLE `sensor_deger_gecmisi` (
  `id` int(11) NOT NULL,
  `sensor_id` int(11) NOT NULL,
  `deger` int(11) NOT NULL,
  `zaman` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sensor_tipi`
--

CREATE TABLE `sensor_tipi` (
  `id` int(11) NOT NULL,
  `ad` varchar(100) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `sensor_tipi`
--

INSERT INTO `sensor_tipi` (`id`, `ad`) VALUES
(1, 'Sıcaklık'),
(2, 'Nem'),
(3, 'Yağmur'),
(4, 'Karbonmonoksit'),
(5, 'Mesafe'),
(6, 'Işık');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `demirbas`
--
ALTER TABLE `demirbas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `konum` (`konum`),
  ADD KEY `demirbas_tipi` (`demirbas_tipi`);

--
-- Tablo için indeksler `demirbas_tipi`
--
ALTER TABLE `demirbas_tipi`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `hareket`
--
ALTER TABLE `hareket`
  ADD KEY `demirbas_id` (`demirbas_id`);

--
-- Tablo için indeksler `konum`
--
ALTER TABLE `konum`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `sensor`
--
ALTER TABLE `sensor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `konum` (`konum`),
  ADD KEY `sensor_tipi` (`sensor_tipi`);

--
-- Tablo için indeksler `sensor_deger_gecmisi`
--
ALTER TABLE `sensor_deger_gecmisi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sensor_id` (`sensor_id`);

--
-- Tablo için indeksler `sensor_tipi`
--
ALTER TABLE `sensor_tipi`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `demirbas`
--
ALTER TABLE `demirbas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Tablo için AUTO_INCREMENT değeri `demirbas_tipi`
--
ALTER TABLE `demirbas_tipi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `konum`
--
ALTER TABLE `konum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `sensor`
--
ALTER TABLE `sensor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `sensor_deger_gecmisi`
--
ALTER TABLE `sensor_deger_gecmisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `sensor_tipi`
--
ALTER TABLE `sensor_tipi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `demirbas`
--
ALTER TABLE `demirbas`
  ADD CONSTRAINT `demirbas_ibfk_1` FOREIGN KEY (`konum`) REFERENCES `konum` (`id`),
  ADD CONSTRAINT `demirbas_ibfk_2` FOREIGN KEY (`demirbas_tipi`) REFERENCES `demirbas_tipi` (`id`);

--
-- Tablo kısıtlamaları `hareket`
--
ALTER TABLE `hareket`
  ADD CONSTRAINT `hareket_ibfk_1` FOREIGN KEY (`demirbas_id`) REFERENCES `demirbas` (`id`);

--
-- Tablo kısıtlamaları `sensor`
--
ALTER TABLE `sensor`
  ADD CONSTRAINT `sensor_ibfk_1` FOREIGN KEY (`konum`) REFERENCES `konum` (`id`),
  ADD CONSTRAINT `sensor_ibfk_2` FOREIGN KEY (`sensor_tipi`) REFERENCES `sensor_tipi` (`id`);

--
-- Tablo kısıtlamaları `sensor_deger_gecmisi`
--
ALTER TABLE `sensor_deger_gecmisi`
  ADD CONSTRAINT `sensor_deger_gecmisi_ibfk_1` FOREIGN KEY (`sensor_id`) REFERENCES `sensor` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
