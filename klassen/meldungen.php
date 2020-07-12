<?php
namespace UI;
use UI\Konstanten;
use UI\Icon;


class Meldung {
  /** @var string Art der Meldung */
  private $art;
  /** @var string Titel der Meldung */
  private $titel;
  /** @var string Inhalt der Meldung */
  private $inhalt;
  /** @var Icon Icon der Meldung */
  private $icon;


	/**
   * @param string $art    Art der Meldung
   * @param string $titel  Titel der Meldung
   * @param string $inhalt Inhalt der Meldung
   */
  public function __construct($titel, $inhalt, $art = "", $icon = null) {
    $this->art = $art;
    $this->titel = $titel;
    $this->inhalt = $inhalt;
    if ($icon == null) {
      if ($art == "erfolg") {$icon = new Icon(UI\Konstanten::ERFOLG);}
      else if ($art == "info") {$icon = new Icon(UI\Konstanten::INFORMATION);}
      else if ($art == "fehler") {$icon = new Icon(UI\Konstanten::FEHLER);}
      else if ($art == "warnung") {$icon = new Icon(UI\Konstanten::WARNUNG);}
      else if ($art == "schutz") {$icon = new Icon(UI\Konstanten::SCHUTZ);}
      else if ($art == "laden") {$icon = new Icon(UI\Konstanten::LADEN);}
      else  {$icon = new Icon(UI\Konstanten::DEFAULT);}
    }
    $this->icon = $icon;
  }

  /**
	* Gibt die Meldung als HTML-Code aus
	* @return string HTML-Code der Meldung
	*/
  public function ausgabe () : string {
    $code  = "<div class=\"dshUiMeldung\">";
      $code .= "<div class=\"dshUiMeldungTitel\"><h4>{$this->icon} {$this->titel}</h4></div>";
      $code .= "<div class=\"dshUiMeldungInhalt\">{$this->inhalt}</div>";
    $code .= "</div>";
    return $code;
  }
}
?>
