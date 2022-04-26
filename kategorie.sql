CREATE OR REPLACE DATABASE kategorie;
USE kategorie;

CREATE TABLE `kategorie` (
  `id` int(11) NOT NULL,
  `nazwa` text NOT NULL,
  `id_rodzic` int(11) DEFAULT NULL,
  `id_prev` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
