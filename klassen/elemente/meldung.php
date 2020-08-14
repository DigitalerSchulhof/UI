<?php
namespace UI;
use UI\Konstanten;
use UI\Icon;
use UI\Ladesymbol;


class Meldung extends Element {
  protected $tag = "div";

  /** @var string Zulässige Meldungsarten */
  const ARTEN = ["Standard", "Erfolg", "Warnung", "Fehler", "Information", "Laden", "Eingeschraenkt", "Gesperrt", "Arbeit"];

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
    parent::__construct();
    if(!in_array($art, self::ARTEN)) {
      $art = self::ARTEN[0];
    }
    $this->art = $art;
    $this->titel = $titel;
    if(substr($inhalt, 0, 1) !== "<") {
      $inhalt = "<p>$inhalt</p>";
    }
    $this->inhalt = $inhalt;

    if($this->art === "Laden") {
      $this->inhalt  = "<div class=\"dshUiLaden\">".new Ladesymbol().new Notiz("Dieser  Inhalt wird geladen...")."</div>";
    }

    $articons = array(
      "Standard"        => Konstanten::STANDARD,
      "Erfolg"          => Konstanten::ERFOLG,
      "Fehler"          => Konstanten::FEHLER,
      "Warnung"         => Konstanten::WARNUNG,
      "Information"     => Konstanten::INFORMATION,
      "Laden"           => Konstanten::LADEN,
      "Eingeschraenkt"  => Konstanten::SCHUTZ,
      "Gesperrt"        => Konstanten::GESPERRT,
      "Arbeit"          => Konstanten::LADEN,
    );

    if($icon === null) {
      if(isset($articons[$this->art])) {
        $icon = new Icon($articons[$this->art]);
      } else {
        $this->icon = new Icon(Konstanten::STANDARD);
      }
    }
    $this->icon = $icon;
    $this->addFunktion("onclick", "ui.meldung.brclick(event)");

    $this->addKlasse("dshUiMeldung");
    $this->addKlasse("dshUiMeldung{$art}");
  }

  public function __toString() : string {
    $i1 = $this->icon;
    $i1->addKlasse("i1");
    $i2 = new Icon("fas fa-bug");
    $i2->addKlasse("i2");

    $code = "{$this->codeAuf()}";
      if($this->art !== "Laden") {
        $code .= "<div class=\"dshUiMeldungTitel\"><h4>$i1{$this->titel}$i2</h4></div>";
      }
      if($this->inhalt !== null) {
        $code .= "<div class=\"dshUiMeldungInhalt\">{$this->inhalt}</div>";
      }
    $code .= "{$this->codeZu()}";
    return $code;
  }
}
?>
