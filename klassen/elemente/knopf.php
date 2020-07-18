<?php
namespace UI\Elemente;
use UI;

/**
*Schaltflächen erstellen
*/
class Knopf extends UI\Elemente\InhaltElement {
  /** @var string Zulässige Knopfarten */
  static const ARTEN = ["Standard", "Erfolg", "Fehler", "Warnung", "Information", "Passiv", "Eingeschraenkt", "Gesperrt"];
  /** @var Icon Icon der Schaltlfäche */
  protected $icon;
  /** @var string Art des Knopfs */
  protected $art;

	/**
	* @param string $text :)
	* @param string $art :)
  * @param Icon   $icon :)
	*/
  public function __construct($text, $art = null, $icon = null) {
    parent::__construct($text);
    if(!in_array($art, self::ARTEN)) {
      $art = self::ARTEN[0];
    }
    $this->art  = $art;
    $this->icon = $icon;
  }



  public function __toString() : string {
    $zusatzklasse = "";
    $eventattribut = "";

    $self = clone $this;

    if ($this->aktionen->count() === 0) {
      $self->dazuKlasse("dshUiKnopfPassiv");
    }
    $self->dazuKlasse("dshUiKnopf$art");

    switch($typ) {
      case "m":
        $self->dazuKlasse("dshUiIconMini");
        $hinweis = new UI\Hinweis($this->text);
        return "<{$self->codeAuf()}>$hinweis{$this->icon}"

    }
    if ($typ == "m") {
      return "<{$self->codeAuf("id")} id=\"{$this->id}T\" value=\"{$datum[0]}\" class=\"dshUiEingabefeld dshUiDatumfeldT".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()} ";
      return "<$tag class=\"dshUiKnopfMini$zusatzklasse\"$eventattribute>".(new Hinweis($this->text))->ausgabe($positionHinweis)."{$this->icon->ausgabe()}</$tag>";
    }
    else if ($typ == "i") {
      return "<$tag class=\"dshUiKnopfIcon$zusatzklasse\"$eventattribute>{$this->icon->ausgabe()} {$this->text}</$tag>";
    }
    else if ($typ == "g") {
      return "<$tag class=\"dshUiKnopfGross$zusatzklasse\"$eventattribute>{$this->icon->ausgabe()}<span>{$this->text}</span></$tag>";
    }
    else {
      return "<$tag class=\"dshUiKnopf$zusatzklasse\"$eventattribute>{$this->text}</$tag>";
    }

  }
}

class IconKnopf extends Knopf {

}

?>
