<?php
namespace UI;

class Spalte extends Element implements \ArrayAccess {
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
    $this->addKlasse("dshSpalte{$typ}");
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
   * Alias für addElement
   * @param Element|string ...$elemente :)
   * @return self
   */
  public function add(...$elemente) : self {
    return $this->addElement(...$elemente);
  }

  /**
   * Setzt den Spaltentyp
   * @param string $typ :)
   * @return self
   */
  public function setTyp($typ) : self {
    $this->removeKlasse("dshSpalte{$this->typ}");
    if($typ !== null && !in_array($typ, self::TYPEN)) {
      $typ = self::TYPEN[0];
    }
    $this->typ = $typ;
    $this->addKlasse("dshSpalte{$typ}");
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
    $r = $this->codeAuf();
    foreach($this->elemente as $element) {
      $r .= $element;
    }
    $r .= $this->codeZu();
    return $r;
  }

  /*
   * ArrayAccess Methoden
   */

  public function offsetSet($o, $v) {
    if(!is_int($o) && !is_null($o)) {
      throw new \TypeError("Der übergebene Offset ist keine Ganzzahl und nicht null");
    }
    if(is_null($o)) {
      $this->elemente[]   = (string) $v;
    } else {
      $this->elemente[$o] = $v;
    }
  }

  public function offsetExists($o) {
    throw new \Exception("Nicht implementiert! Spezifische Methoden nutzen.");
  }

  public function offsetUnset($o) {
    throw new \Exception("Nicht implementiert! Spezifische Methoden nutzen.");
  }

  public function offsetGet($o) {
    throw new \Exception("Nicht implementiert! Spezifische Methoden nutzen.");
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

  /**
   * Gibt eine Zeile mit einer Spalte, zu welcher die übergebenen Elemente hinzugefügt werden, zurück.
   * @param  Element|string $element :)
   * @return Zeile
   */
  public static function standard(...$element) : Zeile {
    return new Zeile(new Spalte(null, ...$element));
  }
}
?>
