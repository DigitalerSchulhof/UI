<?php
namespace UI;
use UI\Konstanten;


class Fenster extends InhaltElement{
  protected $tag = "div";

  /** @var bool Schließen-Button im Fenster anzeigen */
  private $schliessen;
  /** @var string Titel des Fensters */
  private $titel;
  /** @var Knopf[] Aktionen des Fensters */
  private $fensteraktionen;


	/**
	 * @param string $id  :)
	 * @param string $titel :)
	 * @param string $inhalt Inhalt des Fensters
	 */
  public function __construct($id, $titel, $inhalt) {
    $this->schliessen = true;
    $this->titel = $titel;
    $this->fensteraktionen = [];
    parent::__construct($inhalt);
    $this->addKlasse("dshUiFenster");
    $this->setID($id);
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
    $code = $this->codeAuf();
      // Fensteritel
      $code .= "<div class=\"dshUiFensterTitelzeile\">";
        $code .= "<span id=\"{$this->id}FensterTitel\" class=\"dshUiFensterTitel\">$this->titel</span>";
        if ($this->schliessen) {
          $schliessen = new MiniIconKnopf(new Icon(Konstanten::SCHLIESSEN), "Schließen", "Fehler", "UL");
          $schliessen->addFunktion("onclick", "ui.fenster.schliessen('{$this->id}')");
          $schliessen->addKlasse("dshUiFensterSchliessen");
          $code .= $schliessen;
        }
        $code .= "</div>";

        // Fensterinhalt
        $code .= "<div id=\"{$this->id}FensterInhalt\" class=\"dshSpalte dshSpalteA1\">";
          $code .= $this->inhalt;
        $code .= "</div>";

        // Fensteraktionen ausgeben
        $code .= "<div  id=\"{$this->id}FensterAktionen\" class=\"dshUiFensterAktionen\">";
          foreach ($this->fensteraktionen as $f) {
            $code .= $f;
          }
        $code .= "</div>";
      $code .= "</div>";
    $code .= $this->codeZu();
    return $code;
  }
}
?>
