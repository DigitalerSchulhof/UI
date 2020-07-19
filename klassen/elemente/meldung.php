<?php
namespace UI\Elemente;
use UI\Konstanten;
use UI\Icon;


class Meldung extends Element {
  protected $tag = "div";

  /** @var string Zulässige Meldungsarten */
  const ARTEN = ["Standard", "Erfolg", "Fehler", "Warnung", "Information", "Laden", "Eingeschraenkt", "Gesperrt"];

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
    if(!in_array($art, self::ARTEN)) {
      $art = self::ARTEN[0];
    }
    $this->art = $art;
    $this->titel = $titel;
    $this->inhalt = $inhalt;

    if($this->art === "Laden") {
      $this->inhalt  = "<div class=\"dshUiLaden\">".(new \UI\Ladesymbol())->ausgabe();
      $this->inhalt .= "<p class=\"dshNotiz\">Dieser Inhalt wird geladen...</p></div>";
    }

    $articons = array(
      "Standard"        => Konstanten::STANDARD,
      "Erfolg"          => Konstanten::ERFOLG,
      "Fehler"          => Konstanten::FEHLER,
      "Warnung"         => Konstanten::WARNUNG,
      "Information"     => Konstanten::INFORMATION,
      "Laden"           => Konstanten::LADEN,
      "Eingeschraenkt"  => Konstanten::SCHUTZ,
      "Gesperrt"        => Konstanten::GESPERRT
    );

    if($icon === null) {
      if(isset($articons[$this->art])) {
        $icon = new Icon($articons[$this->art]);
      } else {
        $this->icon = new Icon(Konstanten::STANDARD);
      }
    }
    $this->icon = $icon;
  }

  public function __toString() : string {
    $self = clone $this;
    $self->addKlasse("dshUiMeldung");
    $self->addKlasse("dshUiMeldung{$this->art}");
    $code = "{$self->codeAuf()}";
      $code .= "<div class=\"dshUiMeldungTitel\"><h4>{$this->icon} {$this->titel}</h4></div>";
      $code .= "<div class=\"dshUiMeldungInhalt\">{$this->inhalt}</div>";
    $code .= "{$self->codeZu()}";
    return $code;
  }
}
?>
