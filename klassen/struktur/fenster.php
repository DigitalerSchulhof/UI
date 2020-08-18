<?php
namespace UI;
use UI\Konstanten;


class Fenster extends InhaltElement {
  protected $tag = "div";

  /** @var bool Schließen-Button im Fenster anzeigen */
  private $schliessen;
  /** @var string Titel des Fensters */
  private $titel;
  /** @var Knopf[] Aktionen des Fensters */
  private $fensteraktionen;
  /** @var bool Ob der Fensterinhalt kein Padding links und rechts bekommt */
  private $ohnePadding;

	/**
	 * @param string $id  :)
	 * @param string $titel :)
	 * @param string $inhalt Inhalt des Fensters
	 * @param bool   $gross Fenster mit Seitenbreite
	 * @param bool   $ohnePadding Soll der Fensterinhalt ohne Padding links und rechts ausgegeben werden? In Verwendung bei mehreren Spalten in einem Fenster
	 */
  public function __construct($id, $titel, $inhalt, $gross = false, $ohnePadding = false) {
    parent::__construct($inhalt);
    $this->schliessen = true;
    $this->titel = $titel;
    $this->fensteraktionen = [];
    $this->addKlasse("dshUiFenster");
    $this->setAttribut("tabindex", "-1");
    $this->setID($id);
    $this->ohnePadding = $ohnePadding;
    if ($gross) {
      $this->addKlasse("dshUiFensterGross");
    }
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
   * Fügt einen Knopf in die Fensteraktionen ein
   * @param Knopf $knopf Neuer Knopf
   */
  public function addFensteraktion($knopf) {
    $this->fensteraktionen[] = $knopf;
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
        $ohnePadding = "";
        if($this->ohnePadding) {
          $ohnePadding = " dshUiOhnePadding";
        }
        $code .= "<div id=\"{$this->id}FensterInhalt\" class=\"dshSpalte dshSpalteA1$ohnePadding\">";
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
