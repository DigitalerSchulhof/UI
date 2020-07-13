<?php
namespace UI;
use UI\Aktion;
use UI\Icon;
use UI\Hinweis;

/**
*Schaltflächen erstellen
*/
class Knopf {
  /** @var string Enthält den Text der Schaltfläches */
  protected $text;
  /** @var Aktion Enthält das Event, das die Schaltfläche auslöst */
  protected $event;
  /** @var Icon Enthält Icon der Schaltlfäche */
  protected $icon;

	/**
	* @param string $text
	* @param Aktion $event enthält das Event des Knopfes
  * @param Icon   $icon enthält den Klassennamen für FontAwesome-Icons
	*/
  public function __construct($text, $event = null, $icon = null) {
    $this->text = $text;
    $this->event = $event;
    $this->icon = $icon;
  }


  /**
   * Gibt den Knopf aus
   * @param  string $typ Typ des Knopfes
   *                     s = Standard - m = Mini - i = Icon - g = Groß
   * @param  string $art Art des Knopfes
   *                     Standard, Erfolg, Fehler, Warnung, Information
   * @param  string $position Ort des Hinweises
   *                     OR, OL, UR, UL
   * @return string      HTML-Code des Knopfes
   */
  public function ausgabe ($typ = "s", $art = "", $positionHinweis = "OR") : string {
    $zusatzklasse = "";
    $eventattribut = "";
    if ($this->event == null) {$zusatzklasse = " dshUiKnopfPassiv";}
    else {$eventattribute = $this->event->ausgabe();}
    if ($art != "") {$zusatzklasse = " dshUiKnopf".$art;}
    $tag = $this->event->getTag();

    if ($typ == "m") {
      return "<$tag class=\"dshUiKnopfMini$zusatzklasse\"$eventattribute>".(new Hinweis($this->text))->ausgabe($positionHinweis)."{$this->icon->ausgabe()}</$tag>";
    }
    else if ($typ == "i") {
      return "<$tag class=\"dshUiKnopfIcon$zusatzklasse\"$eventattribute>{$this->icon->ausgabe()} {$this->text}</$tag>";
    }
    else if ($typ == "g") {
      return "<$tag class=\"dshUiKnopfGross$zusatzklasse\"$eventattribute>{$this->icon->ausgabe()}<span>{$this->text}</span></$tag>";
    }
    else {
      return "<$tag class=\"dshUiKnopf$zusatzklasse\"$eventattribute>{$this->text}</$tag>";
    }
  }
}
?>
