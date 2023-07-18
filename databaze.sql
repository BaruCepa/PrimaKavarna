CREATE DATABASE primakavarna COLLATE utf8_czech_ci;

CREATE TABLE stranka (
	id varchar(100) primary key,
	titulek text,
	menu text,
	obsah text,
	poradi int
);

INSERT INTO stranka SET
	id = "uvod",
	titulek = "PrimaKavárna",
	menu = "Domů",
	obsah = "...",
	poradi = 1;

INSERT INTO stranka SET
	id = "nabidka",
	titulek = "PrimaKavárna - Nabídka",
	menu = "Nabídka",
	obsah = "...",
	poradi = 2;

INSERT INTO stranka SET
	id = "galerie",
	titulek = "PrimaKavárna - Galerie",
	menu = "Galerie",
	obsah = "...",
	poradi = 3;

INSERT INTO stranka SET
	id = "rezervace",
	titulek = "PrimaKavárna - Rezervace",
	menu = "Rezervace",
	obsah = "...",
	poradi = 4;

INSERT INTO stranka SET
	id = "kontakt",
	titulek = "PrimaKavárna - Kontakt",
	menu = "Kontakt",
	obsah = "...",
	poradi = 5;

INSERT INTO stranka SET
	id = "404",
	titulek = "Stránka neexistuje",
	menu = "",
	obsah = "...",
	poradi = 6;