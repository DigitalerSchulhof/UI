<?php
namespace UI;
use UI\Konstanten;


class Fenster extends InhaltElement {
  protected $tag = "div";

  /** @var bool Schließen-Button im Fenster anzeigen */
  private $schliessen;
  /** @var bool Minimieren-Button im Fenster anzeigen */
  private $minimieren;
  /** @var string Titel des Fensters */
  private $titel;
  /** @var Knopf[] Aktionen des Fensters */
  private $fensteraktionen;
  /** @var bool Ob der Fensterinhalt kein Padding links und rechts bekommt */
  private $ohnePadding;

	/**
	 * @param string $id  :)
	 * @param string $titel :)
	 * @param Zeile $inhalt Inhalt des Fensters
	 * @param bool   $gross Fenster mit Seitenbreite
	 * @param bool   $ohnePadding Soll der Fensterinhalt ohne Padding links und rechts ausgegeben werden? In Verwendung bei mehreren Spalten in einem Fenster
	 */
  public function __construct($id, $titel, $inhalt, $gross = false, $ohnePadding = false) {
    parent::__construct($inhalt);
    $this->schliessen = true;
    $this->minimieren = false;
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
   * Setzt, ob das Minimieren-Icon angezeigt wird
   * @param  bool $schliessen :)
   * @return self
   */
  public function setMinimieren($minimieren) : self {
    $this->minimieren = $minimieren;
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
        if($this->minimieren) {
          $minimieren = new MiniIconKnopf(new Icon("fas fa-window-minimize"), "Minimieren", "Standard", "UL");
          $minimieren->addFunktion("onclick", "ui.fenster.minimieren('{$this->id}')");
          $minimieren->addKlasse("dshUiFensterMinimieren");
          $code .= $minimieren;
          $maximieren = new MiniIconKnopf(new Icon("fas fa-window-maximize"), "Maximieren", "Standard", "UL");
          $maximieren->addFunktion("onclick", "ui.fenster.minimieren('{$this->id}')");
          $maximieren->addKlasse("dshUiFensterMaximieren");
          $code .= $maximieren;
        }
        if ($this->schliessen) {
          $schliessen = new MiniIconKnopf(new Icon("fas fa-times"), "Schließen", "Fehler", "UL");
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
        $code .= "<div id=\"{$this->id}FensterInhalt\" class=\"dshSpalte dshUiFensterInhalt dshSpalteA1$ohnePadding\">";
          $code .= $this->inhalt;
        $code .= "</div><div class=\"dshClear\"></div>";

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
