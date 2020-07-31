<?php
namespace UI;

/**
 * Legt fest, wie Seitenüberschriften auszusehen haben
 */
class SeitenUeberschrift extends InhaltElement {
  protected $tag = "h1";
}

class Ueberschrift extends InhaltElement {
  protected $tag = "h2";

  public function __construct($groesse, $inhalt) {
    if (!\Check::istZahl($groesse, 1, 6)) {
      throw new \Exception("Falscher Zahlbereich für Überschriften");
    }
    parent::__construct($inhalt);
    $this->setTag("h$groesse");
  }
}

class Absatz extends InhaltElement {
  protected $tag = "p";
}

class Link extends InhaltElement {
  protected $tag = "a";

  protected $extern;

  public function __construct($inhalt, $ziel, $extern = false) {
    parent::__construct($inhalt);
    $this->ziel = $ziel;
    $this->setAttribut("href", $ziel);
    if ($extern) {
      $this->addKlasse("dshExtern");
    }
    $this->extern = $extern;
  }

  public function setZiel($ziel) : self {
    $this->setAttribut("href", $ziel);
    return $this;
  }

  public function __toString() : string {
    $self = clone $this;
    $self->addKlasse("dshUiLink");
    if ($self->extern) {
      return "{$self->codeAuf()}{$self->inhalt} ".(new Icon(Konstanten::LINKEXT))."{$self->codeZu()}";
    } else {
      return "{$self->codeAuf()}{$self->inhalt}{$self->codeZu()}";
    }
  }
}

class Liste extends Element {
  /** @var InhaltElement[] $punkte */
  private $punkte;

  const TYPEN = ["UL", "OL"];

  /**
   * Erstellt eine neue Liste
   * @param string          $typ    Typ der Liste
   * @param InhaltElement[] $punkte Punkte der Liste
   */
  public function __construct($typ = "UL", ...$punkte) {
    if (!in_array($typ, self::TYPEN)) {
      $typ = self::TYPEN[0];
    }
    $this->tag = $typ;
    $this->punkte = [];
    foreach ($punkte as $p) {
      $this->punkte[] = $p;
    }
    parent::__construct();
  }

  /**
   * Fügt Punkte zur Liste hinzu
   * @param  InhaltElement[] $punkte Punkte der Liste
   * @return self            Gibt die Liste selbst zurück
   */
  public function add(...$punkte) : self {
    foreach ($punkte as $p) {
      $this->punkte[] = $p;
    }
    return $this;
  }

  /**
   * Erzeugt HTML-Code der Lsite
   * @return string HTML-Code der Liste
   */
  public function __toString() : string {
    $self = clone $this;
    $self->addKlasse("dshUiListe");
    $code = $self->codeAuf();
    foreach ($self->punkte as $p) {
      $code .= "<li>$p</li>";
    }
    return "$code{$self->codeZu()}";
  }
}
?>
