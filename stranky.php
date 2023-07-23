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

	function ulozit($puvodniId)
    {
        global $db;

        if ($puvodniId != "")
        {
            // jde o aktualizaci existujici stranky
            $dotaz = $db->prepare("UPDATE stranka SET id = ?, titulek = ?, menu = ? WHERE id = ?");
            $dotaz->execute([$this->id, $this->titulek, $this->menu, $puvodniId]);
        }
        else
        {
            // jde o pridavani nove stranky
            // zjisteni maximalniho poradi
            $dotaz = $db->prepare("SELECT MAX(poradi) AS poradi FROM stranka");
            $dotaz->execute();
            $vysledek = $dotaz->fetch();
            // vezmeme nejvysi poradi ktere je v tabulce a navysime o 1
            $poradi = $vysledek["poradi"] + 1;

            $dotaz = $db->prepare("INSERT INTO stranka SET id = ?, titulek = ?, menu = ?, poradi = ?");
            $dotaz->execute([$this->id, $this->titulek, $this->menu, $poradi]);
        }
    }

	function smazat()
	{
		global $db;

		$dotaz = $db->prepare("DELETE FROM stranka WHERE id = ?");
		$dotaz->execute([$this->id]);
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