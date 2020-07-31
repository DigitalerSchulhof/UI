<?php
namespace UI;
use UI\Konstanten;


class Fenster extends InhaltElement{
  /** @var bool Schließen-Button im Fenster anzeigen */
  private $schliessen;
  /** @var string Titel des Fensters */
  private $titel;
  /** @var Knopf[] Aktionen des Fensters */
  private $fensteraktionen;


	/**
	* @param string $titel :)
	* @param string $inhalt Inhalt des Fensters
	*/
  public function __construct($titel, $inhalt) {
    $this->schlissen = false;
    $this->titel = $titel;
    $this->fensteraktionen = [];
    parent::__construct($inhalt);
    $this->addKlasse("dshUiFenster");
  }


  /**
   * Setzt den Wert des Schließen-Symbols
   * @param  bool $schliessen true wenn das Fenster geschlossen werden kann, sonst false
   * @return self             :)
   */
  public function setSchliessen($schliessen) : self {
    $this->schliessen = $schliessen;
    return $this;
  }

  /**
	* Gibt das Fenster als Code aus
	* @return string HTML-Code für das Fenster
	*/
  public function __toString () : string {
    $code = codeAuf();
      // Fensteritel
      $code .= "<div class=\"dshUiFensterTitelzeile\">";
        $code .= "<span class=\"dshUiFensterTitel\">$this->titel</span>";
        if ($this->schliessen) {
          $schliessen = new MiniIconKnopf(new Icon(Konstanten::SCHLIESSEN), "Schließen", "Fehler", "UL");
          $schliessen->addFunktion("onclick", "ui.fenster.schliessen()");
          $schliessen->addKlasse("dshUiFensterSchliessen");
          $code .= $schliessen;
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
            $code .= $f;
          }
        $code .= "</div>";
      }
      $code .= "</div>";
    $code = codeZu();
    return $code;
  }
}
?>
