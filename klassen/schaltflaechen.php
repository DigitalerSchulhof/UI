<?php
namespace UI;
use UI\Aktion;

/**
*Schaltflächen erstellen
*/
class Schaltflaeche {
  /** @var string Enthält den Text der Schaltfläches */
  protected $text;
  /** @var Aktion Enthält das Event, das die Schaltfläche auslöst */
  protected $event;
  /** @var string Enthält Icon der Schaltlfäche */
  protected $icon;

	/**
	* @param string $text
	* @param Aktion $event enthält das Event des Knopfes
  * @param string $icon enthält den Klassennamen für FontAwesome-Icons
	*/
  public function __construct($text, $event=null, $icon="") {
    $this->text = $text;
    $this->event = $event;
    $this->icon = $icon;
  }

  /**
	* Gibt die Schaltfläche als normalen Knopf aus
	* @return string HTML-Code für eine Schaltfläche
	*/
  public function knopf ($art) : string {
    $zusatzklasse = "";
    $eventattribut = "";
    if ($this->event == null) {$zusatzklasse = " dshUiKnopfPassiv";}
    else {$eventattribute = $this->event.ausgabe();}
    if ($art != "") {$zusatzklasse = " dshUiKnopf".$art;}

    $tag = $this->event->getTag();

    return "<$tag class=\"dshUiKnopf$zusatzklasse\"$eventattribute>".$this->$text."</$tag>";
  }

  /**
	* Gibt die Schaltfläche als normalen Knopf aus
	* @return string HTML-Code für eine Schaltfläche
	*/
  public function miniknopf ($art) : string {
    $zusatzklasse = "";
    $eventattribut = "";
    if ($this->event == null) {$zusatzklasse = " dshUiKnopfPassiv";}
    else {$eventattribute = $this->event.ausgabe();}
    if ($art != "") {$zusatzklasse = " dshUiKnopf".$art;}

    $tag = $this->event->getTag();

    return "<$tag class=\"dshUiKnopfMini$zusatzklasse\"$eventattribute>".$this->$text."</$tag>";
  }

  /**
   * Gibt die Schaltfläche als normalen Knopf aus
   * @param  string $art Die Art des Knopfes
   * @return string      HTML-Code der Schaltfläche
   */
  public function iconknopf ($art = "") : string {
    $zusatzklasse = "";
    $eventattribut = "";
    if ($this->event == null) {$zusatzklasse = " dshUiKnopfPassiv";}
    else {$eventattribute = $this->event->ausgabe();}
    if ($art != "") {$zusatzklasse = " dshUiKnopf".$art;}

    $tag = $this->event->getTag();

    return "<$tag class=\"dshUiKnopfMini$zusatzklasse\"$eventattribute><i class=\"".$this->icon."\"></i> <span>".$this->text."</span></$tag>";
  }
}
?>
