<?php
namespace UI;
use UI\Aktion;

/**
*Schaltflächen erstellen
*/
class Schaltflaeche {
  /** @var string Enthält die Id der Schaltfläche */
  protected $id;
  /** @var string Enthält den Text der Schaltfläches */
  protected $text;
  /** @var Aktion Enthält das Event, das die Schaltfläche auslöst */
  protected $event;
  /** @var string Enthält Icon der Schaltlfäche */
  protected $icon;

	/**
	* @param string $id
	* @param string $text
	*/
  public function __construct($id, $text, $event=null, $icon="") {
    $this->id = $id;
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
    return "<span id=\"$this->id\" class=\"dshUiKnopf$zusatzklasse\"$eventattribute>".$this->$text."</span>";
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
    return "<span id=\"$this->id\" class=\"dshUiKnopfMini$zusatzklasse\"$eventattribute>".$this->$text."</span>";
  }

  /**
	* Gibt die Schaltfläche als normalen Knopf aus
	* @return string HTML-Code für eine Schaltfläche
	*/
  public function iconknopf ($art) : string {
    $zusatzklasse = "";
    $eventattribut = "";
    if ($this->event == null) {$zusatzklasse = " dshUiKnopfPassiv";}
    else {$eventattribute = $this->event.ausgabe();}
    if ($art != "") {$zusatzklasse = " dshUiKnopf".$art;}
    return "<span id=\"$this->id\" class=\"dshUiKnopfMini$zusatzklasse\"$eventattribute><i class=\"".$this->$icon."\"></i><span>".$this->$text."</span></span>";
  }
}
?>
