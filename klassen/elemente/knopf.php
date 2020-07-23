<?php
namespace UI;

/**
*Schaltflächen erstellen
*/
class Knopf extends InhaltElement {
  protected $tag = "span";

  /** @var string Zulässige Knopfarten */
const ARTEN = ["Standard", "Erfolg", "Fehler", "Warnung", "Information"/*, "Passiv", "Eingeschraenkt", "Gesperrt"*/];

  /** @var string Knopfart */
  protected $art;

	/**
	* @param string $inhalt :)
	* @param string $art :)
	*/
  public function __construct($inhalt, $art = null) {
    parent::__construct($inhalt);
    if(!in_array($art, self::ARTEN)) {
      $art = self::ARTEN[0];
    }
    $this->art = $art;
    $this->addKlasse("dshUiKnopf");
  }

  /**
   * Gibt einen Klon mit passenden Klassen zurück
   * @return self
   */
  public function toStringVorbereitung() : self {
    $self = clone $this;
    if ($self->aktionen->count() === 0) {
      $self->addKlasse("dshUiKnopfLeer");
    }
    $self->addKlasse("dshUiKnopf{$self->art}");
    $self->setInhalt((new InhaltElement($self->inhalt))->setTag("span"));

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
  * @param string $inhalt :)
  * @param string $art :)
  */
  public function __construct($icon, $inhalt, $art = null) {
    parent::__construct($inhalt, $art);
    $this->icon = $icon;
    $this->addKlasse("dshUiKnopfIcon");
  }

  public function __toString() : string {
    $self = $this->toStringVorbereitung();
    return "{$self->codeAuf()}{$self->icon}{$self->inhalt}{$self->codeZu()}";
  }
}

class GrossIconKnopf extends IconKnopf {
  /** @var $position Position des Hinweises */
  protected $position;

  /**
  * @param Icon   $icon :)
  * @param string $inhalt :)
  * @param string $art :)
  * @param string $typ :)
  * @param string $position Position des Hinweises - ["OR"; "OL"; "UR"; "UL"]
  */
  public function __construct($icon, $inhalt, $art = null, $position = "OL") {
    parent::__construct($icon, $inhalt, $art);
    $this->position = $position;
    $this->addKlasse("dshUiKnopfGross");
  }

  public function __toString() : string {
    $self = $this->toStringVorbereitung();

    return "{$self->codeAuf()}{$self->icon}{$self->inhalt}{$self->codeZu()}";
  }
}

class MiniIconKnopf extends IconKnopf {
  /** @var $position Position des Hinweises */
  protected $position;

  /**
  * @param Icon   $icon :)
  * @param string $inhalt :)
  * @param string $art :)
  * @param string $typ :)
  * @param string $position Position des Hinweises - ["OR"; "OL"; "UR"; "UL"]
  */
  public function __construct($icon, $inhalt, $art = null, $position = "OL") {
    parent::__construct($icon, $inhalt, $art);
    $this->position = $position;
    $this->addKlasse("dshUiKnopfIconMini");
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

    $hinweis = new Hinweis($self->inhalt, $this->position);
    return "{$self->codeAuf()}$hinweis{$self->icon}{$self->codeZu()}";
  }
}

?>
