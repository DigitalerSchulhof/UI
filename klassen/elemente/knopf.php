<?php
namespace UI\Elemente;
use UI;

/**
*Schaltflächen erstellen
*/
class Knopf extends UI\Elemente\InhaltElement {
  /** @var string Zulässige Knopfarten */
  static const ARTEN = ["Standard", "Erfolg", "Fehler", "Warnung", "Information", "Passiv", "Eingeschraenkt", "Gesperrt"];

	/**
	* @param string $text :)
	* @param string $art :)
	*/
  public function __construct($text, $art = null) {
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

    return "<$tag class=\"dshUiKnopf$zusatzklasse\"$eventattribute>{$this->text}</$tag>";

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

  }
}

abstract class IconKnopf extends Knopf {
  /** @var string Zulässige IconKnopfTypen */
  static const TYPEN = ["Standard", "Gross", "Mini"];

  /** @var Icon Icon der Schaltlfäche */
  protected $icon;
  /** @var string Typ des Knopfes*/

  /**
  * @param string $text :)
  * @param string $typ :)
  * @param string $art :)
  * @param Icon   $icon :)
  */
  public function __construct($text, $typ=null, $art = null, $icon = null) {
    parent::__construct($text);
    if(!in_array($typ, self::TYPEN)) {
      $art = self::TYPEN[0];
    }
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

    return "<$tag class=\"dshUiKnopf$zusatzklasse\"$eventattribute>{$this->text}</$tag>";

    switch($typ) {
      case "Standard":
        $self->dazuKlasse("dshUiKnopfIcon");
        return "{$self->codeAuf()}{$this->icon} {$this->text}{$self->codeZu()}";
      case "Mini":
        $self->dazuKlasse("dshUiIconMini");
        $hinweis = new UI\Hinweis($this->text);
        return "{$self->codeAuf()}$hinweis{$this->icon}{$self->codeZu()}";
      case "Gross":
        $self->dazuKlasse("dshUiKnopfGross");
        $knopfinhalt = new InhaltElement($this->text);
        $knopfinhalt->setTag("span");
        $knopfinhalt->addKlasse("dshUiKnopfGrossText");
        return "{$self->codeAuf()}{$this->icon}$knopfinhalt{$self->codeZu()}";
    }

  }

}

?>
