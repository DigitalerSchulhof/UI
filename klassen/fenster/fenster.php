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
  /** @var string Enthält die CSS-Klasse des Fensters */
  private $klasse;
  /** @var Fensteraktion[] Enthält alle Aktionen des Fensters */
  private $fensteraktionen;


	/**
	* @param string $ausloeser
	* @param string[] $event
	*/
  public function __construct($titel, $inhalt, $fensteraktionen, $schliessen = true, $klasse = "") {
    $this->schlissen = $schliessen;
    $this->titel = $titel;
    $this->inhalt = $inhalt;
    $this->fensteraktionen = $fensteraktionen;
    $this->klasse = $klasse;
  }

  /**
	* Gibt das Fenster als Code aus
	* @return string HTML-Code für das Fenster
	*/
  public function ausgabe () : string {
    $zusatzklasse = "";
    if ($this->klasse != "") {
      $zusatzklasse = " ".$klasse;
    }
    $code = "<div class=\"dshUiFenster$zusatzklasse\">";
      // Fensteritel
      $code .= "<div class=\"dshUiFensterTitelzeile\">";
        $code .= "<span class=\"dshUiFensterTitel\">$this->titel</span>";
        if ($this->schliessen) {
          $aktion = new Aktion("onclick", "ui.fenster.schliessen()");
          $code .= "<span class=\"dshUiFensterSchliessen\"".$aktion->ausgabe()."><i class=\"fas fa-window-close\"></i></span>";
        }
      $code .= "</div>";

      // Fensterinhalt
      $code .= "<div>";
        $code .= $this->inhalt;
      $code .= "</div>";

      // Fensteraktionen ausgeben
      if (count($fensteraktionen) > 0) {
        $code .= "<div class=\"dshUiFensterAktionen\">";
          foreach ($fensteraktionen as $f) {
            $code .= $f->ausgabe();
          }
        $code .= "</div>";
      }
    $code .= "</div>";

    return $code;
  }
}
?>
