<?php
namespace UI;

/**
*Schaltflächen erstellen
*/
class Knopf extends InhaltElement {
  protected $tag = "span";

  /** @var string Zulässige Knopfarten */
  const ARTEN = ["Standard", "Erfolg", "Fehler", "Warnung", "Information", "Passiv", "Eingeschraenkt", "Gesperrt"];

  /** @var string Knopfart */
  protected $art;

	/**
	* @param string $text :)
	* @param string $art :)
	*/
  public function __construct($text, $art = null) {
    parent::__construct($text);
    if(!in_array($art, self::ARTEN)) {
      $art = self::ARTEN[0];
    }
    $this->art = $art;
  }

  /**
   * Gibt einen Klon mit passenden Klassen zurück
   * @return self
   */
  public function toStringVorbereitung() : self {
    $self = clone $this;
    if ($self->aktionen->count() === 0) {
      $self->addKlasse("dshUiKnopfPassiv");
    }
    $self->addKlasse("dshUiKnopf");
    $self->addKlasse("dshUiKnopf{$self->art}");

    return $self;
  }

  public function __toString() : string {
    $self = $this->toStringVorbereitung();

    return "{$self->codeAuf()}{$self->inhalt}{$self->codeZu()}";
  }
}

class IconKnopf extends Knopf {
  /** @var Icon Icon der Schaltlfäche */
  protected $icon;

  /**
  * @param Icon   $icon :)
  * @param string $text :)
  * @param string $art :)
  */
  public function __construct($icon, $text, $art = null) {
    parent::__construct($text, $art);
    $this->icon = $icon;
  }

  public function __toString() : string {
    $self = $this->toStringVorbereitung();
    $self->addKlasse("dshUiKnopfIcon");
    return "{$self->codeAuf()}{$self->icon} {$self->text}{$self->codeZu()}";
  }
}

class GrossIconKnopf extends IconKnopf {
  /** @var $position Position des Hinweises */
  protected $position;

  /**
  * @param Icon   $icon :)
  * @param string $text :)
  * @param string $art :)
  * @param string $typ :)
  * @param string $position Position des Hinweises - ["OR"; "OL"; "UR"; "UL"]
  */
  public function __construct($icon, $text, $art = null, $position = "OL") {
    parent::__construct($icon, $text, $art);
    $this->position = $position;
  }

  public function __toString() : string {
    $self = $this->toStringVorbereitung();

    $self->addKlasse("dshUiKnopfGross");
    $knopfinhalt = new InhaltElement($this->text);
    $knopfinhalt->setTag("span");
    $knopfinhalt->addKlasse("dshUiKnopfGrossText");
    return "{$self->codeAuf()}{$this->icon}$knopfinhalt{$self->codeZu()}";
  }
}

class MiniIconKnopf extends IconKnopf {
  /** @var $position Position des Hinweises */
  protected $position;

  /**
  * @param Icon   $icon :)
  * @param string $text :)
  * @param string $art :)
  * @param string $typ :)
  * @param string $position Position des Hinweises - ["OR"; "OL"; "UR"; "UL"]
  */
  public function __construct($icon, $text, $art = null, $position = "OL") {
    parent::__construct($icon, $text, $art);
    $this->position = $position;
  }

  /**
   * Setzt die Position des Hinweises
   * @param  string $position :)
   * @return self
   */
  public function setPosition($position) : self {
    $this->position = $position;
  }

  public function __toString() : string {
    $self = $this->toStringVorbereitung();

    $self->addKlasse("dshUiIconMini");
    $hinweis = new Hinweis($self->inhalt, $this->position);
    return "{$self->codeAuf()}$hinweis{$self->icon}{$self->codeZu()}";
  }
}

?>
