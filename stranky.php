<?php

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

$seznamStranek = [
	"uvod" => new Stranka("uvod", "PrimaKavárna", "Domů"),
	"nabidka" => new Stranka("nabidka", "PrimaKavárna - Nabídka", "Nabídka"),
	"galerie" => new Stranka("galerie", "PrimaKavárna - Galerie", "Galerie"),
	"rezervace" => new Stranka("rezervace", "PrimaKavárna - Rezervace", "Rezervace"),
	"kontakt" => new Stranka("kontakt", "PrimaKavárna - Kontakt", "Kontakt"),
	"404" => new Stranka("404", "Stránka neexistuje", ""),
];
