<?php
namespace UI;

class Aktion {
  /** @var string[][] Enthält das JS-Ziel des Events */
  private $events;

	/**
	* @param string $ausloeser Event
	* @param string $event
	*/
  public function __construct($ausloeser = null, $event = null) {
    $this->events = array();
    if($ausloeser !== null && $event !== null) {
      $ausloeser = strtolower($ausloeser);
      $this->events[$ausloeser] = array();
      $this->events[$ausloeser][] = $event;
    }
  }

  /**
	* Gibt das Eingabefeld als Textfeld aus
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function ausgabe() : string {
    $code = "";
    foreach ($this->events as $a => $e) {
      $code .= " ".$a."=\"";
      foreach ($e as $funktion) {
        $code .= $funktion.";";
      }
      $code .= "\"";
    }
    return $code;
  }

  /**
	* Fügt ein Event zu dieser Aktion hinzu
  * @param string $ausloeser die Ursache des Events
  * @param string $e Das anzufügende Event
	*/
  public function dazu($ausloeser, $e, $vorne = false) {
    $ausloeser = strtolower($ausloeser);
    if (!in_array($ausloeser, $this->events)) {
      $this->events[] = $ausloeser;
    }
    if ($vorne) {
      array_unshift($this->events[$ausloeser], $e);
    }
    else {
      $this->events[$ausloeser][] = $e;
    }
  }

  /**
   * Gibt zurück, ob ein Auslöser angegeben ist
   * @param  string $ausloeser Der Auslöser, der gefragt ist
   * @return bool Ob der Auslöser vorhanden ist
   */
  public function hatAusloeser($ausloeser) : bool {
    return isset($this->events[$ausloeser]);
  }

  /**
   * Ermittelt den optimalen HTML-Tag für das Element
   * @return string Der optimale HTML-Tag
   */
  public function getTag() {
    if($this->hatAusloeser("href")) {
      return "a";
    } else {
      return "span";
    }
  }
}

class Fensteraktion {
  /** @var Aktion Aktionen des Fensters */
  private $aktion;
  /** @var string Darstellungsart des Aktionsknopfes */
  private $art;
  /** @var string Text des Aktionsknopfes */
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
