<?php
namespace UI;

/**
* Eingabefelder erstellen
*/
class Aktion {
  /** @var string[][] Enthält das JS-Ziel des Events */
  private $events;

	/**
	* @param string $ausloeser
	* @param string[] $event
	*/
  public function __construct($ausloeser, $event) {
    $this->events = array();
    $this->events[] = $ausloeser;
    $this->events[$ausloeser][] = $e;
  }

  /**
	* Gibt das Eingabefeld als Textfeld aus
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function ausgabe () : string {
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
  public function dazu ($ausloeser, $e, $vorne = false) {
    if (!in_array($ausloeser, $this->events)) {
      $this->events[] = $ausloeser;
    }
    if ($vorne) {
      \array_unshift($this->events[$ausloeser], $e);
    }
    else {
      $this->events[$ausloeser][] = $e;
    }
  }
}
?>
