<?php
namespace UI;
use UI\Aktion;

/**
*Eingabefelder erstellen
*/
class Fensteraktion {
  /** @var Aktion Enthält die Aktionen des Fensters */
  private $aktion;
  /** @var string Enthält die Darstellungsart des Aktionsknopfes */
  private $art;
  /** @var string Enthält den Text des Aktionsknopfes */
  private $text;

	/**
	* @param Aktion $aktion
	* @param string $art
	*/
  public function __construct($aktion, $art, $text) {
    $this->aktion = $aktion;
    $this->art = $art;
    $this->text = $text;
  }

  /**
	* Gibt das Eingabefeld als Textfeld aus
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function ausgabe () : string {
    $zusatzklasse = "";
    if ($art != "") {$zusatzklasse = " dshUiKnopf".$art;}
    return "<span class=\"dshUiFensteraktion$zusatzklasse\"".$this->aktion->ausgabe().">$this->text</span>";
  }
}
?>
