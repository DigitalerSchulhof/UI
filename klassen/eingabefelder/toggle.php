<?php
namespace UI;

/**
*Eingabefelder erstellen
*/
class Toggle extends Eingabefeld {
  /** @var string Enthält das auszulösende */
  protected $event;
  /** @var string[] Enthält den Text der Toggles */
  protected $text;

	/**
	* @param string $id
	* @param string[] $text
	* @param string $event
	* @param string $wert
	* @param string $klasse
	*/
  public function __construct($id, $text, $event, $wert=0, $klasse="") {
    if ($wert >= count($text)) {$wert = 0;}
    parent::__construct($id, $wert, $klasse);
    $this->text = $text;
    $this->event = $event;
  }

  /**
	* Gibt das Eingabefeld als Toggle aus
	* @return string HTML-Code für ein Toggle
	*/
  public function toggle () : string {
    $code = "";
    if ($this->wert >= count($text)) {$this->wert = 0;}
    $anzahl = count($text);
    for ($i=0; $i<$anzahl; $i++) {
      $toggled = "";
      if ($this->wert == $i) {
        $toggled = " dshKnopfToggled";
      }
      $code .= "<span id=\"$this->id"."$i\"class=\"dshUiKnopf$toggled $this->klasse\" onclick=\"dshUiToggle('$this->id', '$i', '$anzahl');$this->event\">$this->$text</span> ";
    }

    return $code."<input type=\"hidden\" id=\"$this->id\" name=\"$this->id\" value=\"$this->wert\">";
  }
}
?>
