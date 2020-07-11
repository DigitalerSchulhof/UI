<?php
namespace UI;

/**
*Schaltflächen erstellen
*/
class Schaltflaeche {
  /** @var string Enthält die Id der Schaltfläche */
  protected $id;
  /** @var string Enthält den Text der Schaltfläches */
  protected $text;
  /** @var string Enthält das Event, das die Schaltfläche auslöst */
  protected $event;
  /** @var string Enthält Icon der Schaltlfäche */
  protected $icon;

	/**
	* @param string $id
	* @param string $text
	*/
  public function __construct($id, $text, $event, $icon) {
    $this->id = $id;
    $this->text = $text;
    $this->event = $event;
  }

  /**
	* Gibt die Schaltfläche als normalen Knopf aus
	* @return string HTML-Code für eine Schaltfläche
	*/
  public function knopf ($art) : string {
    $zusatzklasse = "";
    $eventattribut = "";
    if ($this->event == "") {$zusatzklasse = " dshUiKnopfPassiv";}
    else {$eventattribut = " onclick=\"$this->event\"";}
    if ($art != "") {$zusatzklasse = " dshUiKnopf".$art;}
    return "<span id=\"$this->id\" class=\"dshUiKnopf$zusatzklasse\"$eventattribut>".$this->$text."</span>";
  }
}
?>
