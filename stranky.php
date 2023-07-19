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
		// nacteni obsahu stranky z databaze
		global $db;

		$dotaz = $db->prepare("SELECT obsah FROM stranka WHERE id = ?");
		$dotaz->execute([$this->id]);

		$vysledek = $dotaz->fetch();

		// pokud by databaze nic nevratila, tak vratime prazdny obsah
		if ($vysledek == false)
		{
			return "";
		}
		else
		{
			return $vysledek["obsah"];
		}
	}

	function setObsah($obsah)
	{
		// ukladani obsahu stranky do databaze
		global $db;

		$dotaz = $db->prepare("UPDATE stranka SET obsah = ? WHERE id = ?");
		$dotaz->execute([$obsah, $this->id]);
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