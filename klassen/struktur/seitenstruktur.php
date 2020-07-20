<?php
namespace UI;

class Spalte extends Element {
  protected $tag = "div";

  /** @var string Mögliche Spaltentypen */
  const TYPEN = ["A1", "A2", "A3", "A4", "A5", "B23", "B34", "P10", "P20", "P30", "P40", "P50", "P60", "P70", "P80", "P90"];

  /** @var string Typ der Spalte */
  private $typ;
  /** @var string[] Elemente der Spalte */
  private $elemente;

  /**
   * Erzeugt eine neue Spalte
   * @param string|null $typ Spaltentyp - Automatisch festgelegt bei <code>null</code>
   * @param Element|string $elemente Elemente der Spalte
   */
  public function __construct($typ = null, ...$elemente) {
    parent::__construct();
    if($typ !== null && !in_array($typ, self::TYPEN)) {
      $typ = self::TYPEN[0];
    }
    $this->typ      = $typ;
    $this->elemente = $elemente;
    $this->addKlasse("dshSpalte");
  }

  /**
   * Füngt ein oder mehrere Element(e) hinzu
   * @param Element|string ...$elemente :)
   * @return self
   */
  public function addElement(...$elemente) : self {
    $this->elemente = array_merge($this->elemente, $elemente);
    return $this;
  }

  /**
   * Setzt den Spaltentyp
   * @param string $typ :)
   * @return self
   */
  public function setTyp($typ) : self {
    if($typ !== null && !in_array($typ, self::TYPEN)) {
      $typ = self::TYPEN[0];
    }
    $this->typ = $typ;
    return $this;
  }

  /**
   * Gibt den aktuellen Spaltentyp zurück
   * @return string
   */
  public function getTyp() : ?string {
    return $this->typ;
  }

  public function __toString() : string {
    $self = clone $this;

    $self->addKlasse("dshSpalte{$self->typ}");
    $r = $self->codeAuf();
    foreach($self->elemente as $element) {
      $r .= $element;
    }
    $r .= $self->codeZu();
    return $r;
  }
}

class Zeile extends Element {
  protected $tag = "div";

  /** @var Spalte[] Spalten dieser Zeile */
  private $spalten;

  /**
   * Erzeugt eine neue Zeile
   * @param [type] $spalten [description]
   */
  public function __construct(...$spalten) {
    parent::__construct();
    $this->spalten = $spalten;
    $this->addKlasse("dshZeile");
  }

  /**
   * Füngt eine oder mehrere Spalte(n) hinzu
   * @param Spalte ...$spalten :)
   * @return self
   */
  public function addSpalte(...$spalten) : self {
    $this->spalten = array_merge($this->spalten, $spalten);
    return $this;
  }

  public function __toString() : string {
    $r = $this->codeAuf();
    $nullspaltenanz = count(array_filter($this->spalten, function($spalte) {
      return $spalte->getTyp() === null;
    }));

    foreach($this->spalten as $spalte) {
      if($spalte->getTyp() === null) {
        $spalte->setTyp("A$nullspaltenanz");
      }
      $r .= $spalte;
    }
    $r .= "<div class=\"dshClear\"></div>";
    $r .= $this->codeZu();
    return $r;
  }
}
?>
