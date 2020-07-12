<?php
namespace UI;
use UI\Fensteraktion;

/**
*Eingabefelder erstellen
*/
class Fenster {
  /** @var boolean Enthält den Auslöser des Events */
  private $schliessen;
  /** @var string Enthält den Titel des Fensters */
  private $titel;
  /** @var string Enthält den Inhalt des Fensters */
  private $inhalt;
  /** @var Fensteraktion[] Enthält alle Aktionen des Fensters */
  private $fensteraktionen;


	/**
	* @param string $ausloeser
	* @param string[] $event
	*/
  public function __construct($titel, $inhalt, $fensteraktionen, $schliessen = true) {
    $this->ausloeser = $ausloeser;
    $this->event = $event;
  }
}
?>
