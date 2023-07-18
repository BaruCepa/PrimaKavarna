<?php

// vytvoreni pripojeni na databazi
$db = new PDO(
	"mysql:host=localhost;dbname=primakavarna;charset=utf8",
	"root",
	"", // heslo
	array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	),
);

class stranka
{
	public $id;
	public $titulek;
	public $menu;

	function __construct($id, $titulek, $menu)
	{
		$this->id = $id;
		$this->titulek = $titulek;
		$this->menu = $menu;
	}

	function getObsah()
	{
		return file_get_contents("$this->id.html");
	}

	function setObsah($obsah)
	{
		file_put_contents("$this->id.html", $obsah);
	}
}

$seznamStranek = []; // naplnime dynamicky z databaze

$dotaz = $db->prepare("SELECT id, titulek, menu FROM stranka ORDER BY poradi");
$dotaz->execute();

$stranky = $dotaz->fetchAll();

// vezmeme pole radek, ktere nam vratila databaze a postupne nakrmime pole seznamStranek jednotlivymi instancemi tridy Stranka
foreach ($stranky as $stranka)
{
	// pridame do pole novou instanci tridy stranka
	$seznamStranek[$stranka["id"]] = new Stranka($stranka["id"], $stranka["titulek"], $stranka["menu"]);
};