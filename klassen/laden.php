<?php
namespace UI;

use UI;

class Ladesymbol extends UI\Element {
  protected $tag = "div";

  public function __construct() {
    parent::__construct();
    $this->addKlasse("dshUiLadenIcon");
  }

  public function __toString() : string {
    return "{$this->codeAuf()}<div></div><div></div><div></div><div></div>{$this->codeZu()}";
  }
}

class Balken extends UI\Element {
  protected $tag = "div";
  /** @var Art des Balkens */
  protected $art;
  /** @var float Belegte Menge des Balkens */
  protected $belegt;
  /** @var float Vollständige Größe des Balkens (100 Prozent) */
  protected $ganz;
  /** @var float Falls Art = Zeit: Limit gibt maximale Zeitspanne an */
  protected $limit;
  /** @var string Zulässige Balkenarten */
  const ARTEN = ["Schritte", "Zeit", "Prozent", "Speicher"];

  /**
   * Erstellt einen neuen Balken
   * @param string $art   :)
   * @param float $belegt :)
   * @param float $ganz   :)
   * @param float $limit  Nur für Zeit notwendig - gibt die maximale Zeitspanne an
   */
  public function __construct($art, $belegt, $ganz, $limit = null) {
    parent::__construct();
    if ($belegt > $ganz) {
      $belegt = $ganz;
    }
    if (!in_array($art, self::ARTEN)) {
      throw new \Exception("Ungültige Balkenart");
    }
    $this->art = $art;
    $this->belegt = $belegt;
    $this->ganz = $ganz;
    $this->limit = $limit;
    $this->addKlasse("dshUiBalken", "dshUiBalken{$this->art}");
  }

  /**
   * Erzeugt einen Balken und einen Informationstext
   * @return string :)
   */
  public function __toString() : string {
    $code = $this->codeAuf();
      $prozent = UI\Check::prozent($this->belegt, $this->ganz);
      $code .= "<div class=\"dshUiBalkenA\" id=\"{$this->id}A\">";
        $code .= "<div class=\"dshUiBalkenI\" id=\"{$this->id}I\" style=\"width: {$prozent["style"]}\"></div>";
      $code .= "</div>";
      $uebrig = $this->ganz - $this->belegt;
      $uebrigprozent = UI\Check::prozent($uebrig, $this->ganz);
      if ($this->art == "Schritte") {
        if ($this->belegt == 1) {
          $einheitpos = "Schritt";
        } else {
          $einheitpos = "Schritte";
        }
        if ($uebrig == 1) {
          $einheitneg = "Schritt";
        } else {
          $einheitneg = "Schritte";
        }
        if ($this->gesamt == 1) {
          $einheitgesamt = "Schritt";
        } else {
          $einheitgesamt = "Schritten";
        }
        $code .= "<p class=\"dshUiNotiz\" id=\"{$this->id}Infotext\">";
          $code .= "<span id=\"{$this->id}BelegtAbs\">{$this->belegt}</span> ";
          $code .= "(<span id=\"{$this->id}BelegtPro\">{$prozent["anzeige"]}</span>) ";
          $code .= "{$einheitpos} von {$this->ganz} {$einheitgesamt} ausgeführt - ";
          $code .= "<span id=\"{$this->id}UebrigAbs\">{$uebrig}</span> {$einheitneg} ausstehend ";
          $code .= "(<span id=\"{$this->id}UebrigPro\">{$uebrigprozent["anzeige"]}</span>).";
        $code .= "</p>";
      } else if ($this->art == "Zeit") {
        $uebrigprozent = UI\Check::prozent($uebrig, $this->limit);
        $uebrig = UI\Check::zeit($uebrig);
        $code .= "<p class=\"dshUiNotiz\" id=\"{$this->id}Infotext\">";
          $code .= "Aktiv bis ".date("d.m.Y", $this->ganz)." um ".date("H:i", $this->ganz)." Uhr - ";
          $code .= "noch <span id=\"{$this->id}UebrigAbs\">{$uebrig}</span>.";
        $code .= "</p>";
      } else if ($this->art == "Speicher") {
        $belegt = UI\Check::speicher($this->belegt);
        $gesamt = UI\Check::speicher($this->ganz);
        $frei = UI\Check::speicher($uebrig);
        $code .= "<p class=\"dshUiNotiz\" id=\"{$this->id}Infotext\">";
          $code .= "<span id=\"{$this->id}BelegtAbs\">{$belegt}</span> ";
          $code .= "(<span id=\"{$gesamt}BelegtPro\">{$prozent["anzeige"]}</span>) ";
          $code .= " von {$this->ganz} belegt - ";
          $code .= "<span id=\"{$this->id}UebrigAbs\">{$frei}</span> frei ";
          $code .= "(<span id=\"{$this->id}UebrigPro\">{$uebrigprozent["anzeige"]}</span>).";
        $code .= "</p>";
      } else {
        $code .= "<p class=\"dshUiNotiz\" id=\"{$this->id}Infotext\">";
          $code .= "<span id=\"{$this->id}BelegtPro\">{$prozent["anzeige"]}</span> ausgeführt - ";
          $code .= "<span id=\"{$this->id}UebrigPro\">{$uebrigprozent["anzeige"]}</span> ausstehend.";
        $code .= "</p>";
      }

    $code .= $this->codeZu();
    return $code;
  }
}
?>
